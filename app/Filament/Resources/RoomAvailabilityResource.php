<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomAvailabilityResource\Pages;
use App\Models\RoomAvailability;
use App\Models\Room; 
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter; 
use Filament\Forms\Components\DatePicker; 
use Filament\Forms\Get; 
use Filament\Forms\Components\Grid;

class RoomAvailabilityResource extends Resource
{
    protected static ?string $model = RoomAvailability::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Quản lý Đặt phòng'; 

    protected static ?int $navigationSort = 7;
    
    protected static ?string $modelLabel = 'Tình trạng phòng';

    protected static ?string $pluralModelLabel = 'Tình trạng phòng theo ngày';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\Select::make('room_id')
                            ->label('Loại phòng')
                            ->relationship(
                                name: 'room',
                                titleAttribute: 'room_type_name',
                                modifyQueryUsing: fn (Builder $query) => $query->with('hotel')->orderBy('hotel_id') 
                            )
                            ->getOptionLabelFromRecordUsing(fn (Room $record) => "{$record->hotel?->name} - {$record->room_type_name}")
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->disabledOn('edit'),

                        Forms\Components\DatePicker::make('date')
                            ->label('Ngày áp dụng')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->unique(
                                ignoreRecord: true, 
                                modifyRuleUsing: function (\Illuminate\Validation\Rules\Unique $rule, Get $get) {
                                    return $rule->where('room_id', $get('room_id'));
                                }
                             )
                            ->disabledOn('edit'),

                        Forms\Components\TextInput::make('available_count')
                             ->label('Số lượng phòng trống')
                             ->helperText('Số phòng còn lại thực tế có thể bán vào ngày này.')
                            ->required()
                            ->integer()
                            ->minValue(0), 

                        Forms\Components\TextInput::make('price')
                             ->label('Giá ghi đè (VND)')
                             ->helperText('Để trống nếu muốn sử dụng giá gốc của loại phòng vào ngày này.')
                            ->numeric() 
                            ->prefix('₫')
                            ->nullable(), 

                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 Tables\Columns\TextColumn::make('room.hotel.name')
                    ->label('Khách sạn')
                    ->searchable()
                    ->sortable(),
                 Tables\Columns\TextColumn::make('room.room_type_name')
                    ->label('Loại phòng')
                    ->searchable()
                    ->sortable()
                    ->url(fn (RoomAvailability $record): string => RoomResource::getUrl('edit', ['record' => $record->room_id]))
                    ->openUrlInNewTab(),
                 Tables\Columns\TextColumn::make('date')
                    ->label('Ngày')
                    ->date('d/m/Y') // Định dạng ngày Việt Nam
                    ->sortable(),
                 Tables\Columns\TextColumn::make('available_count')
                    ->label('Phòng trống')
                    ->sortable()
                    ->alignCenter(), 
                 Tables\Columns\TextColumn::make('price')
                    ->label('Giá ghi đè')
                    ->money('VND', locale: 'vi_VN')
                    ->placeholder('Giá gốc')
                    ->sortable()
                    ->alignRight(), // Căn phải

                 Tables\Columns\TextColumn::make('updated_at')
                    ->label('Cập nhật lần cuối')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date', 'desc') 
            ->filters([
                SelectFilter::make('room')
                    ->label('Loại phòng')
                     ->relationship(
                        name: 'room',
                        titleAttribute: 'room_type_name',
                        modifyQueryUsing: fn (Builder $query) => $query->with('hotel')
                    )
                    ->getOptionLabelFromRecordUsing(fn (Room $record) => "{$record->hotel?->name} - {$record->room_type_name}")
                    ->searchable()
                    ->preload(),
                Filter::make('date')
                    ->form([
                        Grid::make(2)->schema([
                             DatePicker::make('date_from')
                                ->label('Từ ngày')
                                ->native(false),
                             DatePicker::make('date_until')
                                ->label('Đến ngày')
                                ->native(false)
                                ->afterOrEqual('date_from'),
                        ])
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoomAvailabilities::route('/'),
            'create' => Pages\CreateRoomAvailability::route('/create'),
            'edit' => Pages\EditRoomAvailability::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['room.hotel']);
    }
}
