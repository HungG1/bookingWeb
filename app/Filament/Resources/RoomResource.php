<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Models\Room;
use App\Models\Hotel;
use Filament\Forms\Components\{
    TextInput,
    Textarea,
    FileUpload,
    Toggle,
    Select,
    Section
};
use Filament\Tables\Columns\{
    TextColumn,
    BooleanColumn,
    BadgeColumn
};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = 'Quản lý Khách sạn & Phòng'; 

    protected static ?int $navigationSort = 4;
    
    protected static ?string $navigationLabel = 'Phòng';
    
    protected static ?string $pluralModelLabel = 'Danh sách phòng';
    
    protected static ?string $modelLabel = 'Phòng';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin phòng')
                    ->schema([
                        // Chọn khách sạn (relationship Hotel)
                        Select::make('hotel_id')
                            ->label('Khách sạn')
                            ->relationship('hotel', 'name')
                            ->preload()
                            ->required(),
                            
                        TextInput::make('room_type_name')
                            ->label('Loại phòng')
                            ->required()
                            ->maxLength(255),
                            
                        Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(3)
                            ->columnSpan('full'),
                    ])->columns(2),

                // Section thông tin giá và số lượng
                Section::make('Thông tin phòng & giá')
                    ->schema([
                        TextInput::make('base_price')
                            ->label('Giá cơ bản')
                            ->numeric()
                            ->required()
                            ->helperText('Giá cơ bản của phòng'),
                            
                        TextInput::make('number_of_rooms')
                            ->label('Số lượng phòng')
                            ->numeric()
                            ->required(),
                            
                        TextInput::make('max_occupancy')
                            ->label('Sức chứa tối đa')
                            ->numeric()
                            ->required(),
                    ])->columns(3),

                // Section hình ảnh và trạng thái
                Section::make('Hình ảnh & Trạng thái')
                    ->schema([
                        FileUpload::make('images')
                            ->label('Hình ảnh phòng')
                            ->disk('public')
                            ->directory('rooms')
                            ->image()
                            ->multiple()
                            ->maxFiles(10)
                            ->nullable(),
                            
                        Toggle::make('is_active')
                            ->label('Trạng thái hoạt động')
                            ->default(true)
                            ->required(),
                    ])->columns(2),

                Section::make('Tiện ích')
                    ->schema([
                        Select::make('amenities')
                            ->label('Tiện ích')
                            ->multiple()
                            ->relationship('amenities', 'name')
                            ->preload(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                ImageColumn::make('images')
                    ->label('Ảnh phòng')
                    ->disk('public') 
                    ->square() 
                    ->limit(3) 
                    ->limitedRemainingText(),
                    
                TextColumn::make('hotel.name')
                    ->label('Khách sạn')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('room_type_name')
                    ->label('Loại phòng')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('number_of_rooms')
                    ->label('Số lượng')
                    ->sortable(),
                    
                TextColumn::make('base_price')
                    ->label('Giá cơ bản')
                    ->money('VND')  
                    ->sortable(),
                    
                BadgeColumn::make('max_occupancy')
                    ->label('Sức chứa')
                    ->colors([
                        'primary' => fn ($state): bool => $state >= 4,
                        'warning' => fn ($state): bool => $state < 4,
                    ])
                    ->sortable(),
                    
                BooleanColumn::make('is_active')
                    ->label('Hoạt động'),
                    
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Trạng thái')
                    ->options([
                        1 => 'Hoạt động',
                        0 => 'Ngưng hoạt động',
                    ]),
                    Tables\Filters\Filter::make('room_type')
                    ->label('Loại phòng')
                    ->query(fn ($query, $data) => 
                        $query->where('room_type_name', 'like', "%{$data['room_type']}%")
                    )
                    ->form([
                        TextInput::make('room_type')
                            ->label('Nhập loại phòng')
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit'   => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
