<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HotelResource\Pages;
use App\Models\Hotel;
use Filament\Forms\Components\{TextInput, Textarea, RichEditor, FileUpload, Toggle, Select};
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\{TextColumn, BooleanColumn, BadgeColumn};

class HotelResource extends Resource
{
    protected static ?string $model = Hotel::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Quản lý Khách sạn & Phòng';

    protected static ?int $navigationSort = 3;
    
    protected static ?string $navigationLabel = 'Khách sạn';
    
    protected static ?string $pluralModelLabel = 'Khách sạn';
    
    protected static ?string $modelLabel = 'Khách sạn';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin khách sạn')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên khách sạn')
                            ->required()
                            ->maxLength(255),
                            
                        Textarea::make('address')
                            ->label('Địa chỉ')
                            ->required()
                            ->rows(2),
                            
                        RichEditor::make('description')
                            ->label('Mô tả')
                            ->nullable()
                            ->columnSpan('full'),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Thông tin bổ sung')
                    ->schema([
                        FileUpload::make('images')
                            ->label('Hình ảnh')
                            ->disk('public')
                            ->directory('hotels')
                            ->image()
                            ->multiple() 
                            ->maxFiles(10)
                            ->nullable(),
                            
                        TextInput::make('star_rating')
                            ->label('Xếp hạng sao')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->required(),
                            
                        TextInput::make('contact_email')
                            ->label('Email liên hệ')
                            ->email()
                            ->nullable(),
                            
                        TextInput::make('contact_phone')
                            ->label('Số điện thoại')
                            ->nullable(),
                            
                        Toggle::make('is_active')
                            ->label('Trạng thái hoạt động')
                            ->default(true)
                            ->required(),
                            
                        Select::make('amenities')
                            ->label('Tiện ích')
                            ->multiple()
                            ->relationship('amenities', 'name')
                            ->preload(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                    
                TextColumn::make('name')
                    ->label('Tên khách sạn')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('address')
                    ->label('Địa chỉ')
                    ->limit(50),
                    
                BadgeColumn::make('star_rating')
                    ->label('Xếp hạng sao')
                    ->colors([
                        'primary' => fn ($state): bool => $state >= 5,
                        'success' => fn ($state): bool => $state == 4,
                        'warning' => fn ($state): bool => $state == 3,
                        'danger'  => fn ($state): bool => $state <= 2,
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
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListHotels::route('/'),
            'create' => Pages\CreateHotel::route('/create'),
            'edit'   => Pages\EditHotel::route('/{record}/edit'),
            // Nếu cần thêm trang view chi tiết, có thể thêm ở đây
        ];
    }
}
