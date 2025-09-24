<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PriceRuleResource\Pages;
use App\Models\PriceRule;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\{TextColumn};
use Filament\Forms\Components\{TextInput, DatePicker, Select, MultiSelect, Section};

class PriceRuleResource extends Resource
{
    protected static ?string $model = PriceRule::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Quản lý Giá & Khuyến mãi'; // Nhóm trong menu

    protected static ?int $navigationSort = 8;

    protected static ?string $modelLabel = 'Quy tắc giá';

    protected static ?string $pluralModelLabel = 'Quy tắc giá';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Thông tin giá linh hoạt')
                ->schema([
                    // Chọn phòng qua relationship
                    Select::make('room_id')
                        ->label('Phòng')
                        ->relationship('room', 'room_type_name')
                        ->required()
                        ->searchable(),

                    // Ngày bắt đầu và kết thúc
                    DatePicker::make('start_date')
                        ->label('Ngày bắt đầu')
                        ->required(),
                    DatePicker::make('end_date')
                        ->label('Ngày kết thúc')
                        ->required(),

                    // Chọn các ngày trong tuần (sẽ lưu dạng array nhờ cast 'array')
                    MultiSelect::make('days_of_week')
                        ->label('Ngày trong tuần áp dụng')
                        ->options([
                            'Mon' => 'Thứ 2',
                            'Tue' => 'Thứ 3',
                            'Wed' => 'Thứ 4',
                            'Thu' => 'Thứ 5',
                            'Fri' => 'Thứ 6',
                            'Sat' => 'Thứ 7',
                            'Sun' => 'Chủ nhật',
                        ])
                        ->required()
                        ->helperText('Chọn các ngày áp dụng cho rule này.'),

                    // Loại thay đổi giá: Số tiền cố định hoặc phần trăm
                    Select::make('price_modifier_type')
                        ->label('Loại thay đổi giá')
                        ->options([
                            'fixed_amount' => 'Số tiền cố định',
                            'percentage'   => 'Phần trăm',
                        ])
                        ->required(),

                    // Giá trị thay đổi
                    TextInput::make('price_modifier_value')
                        ->label('Giá trị thay đổi')
                        ->numeric()
                        ->required(),

                    // Độ ưu tiên (priority)
                    TextInput::make('priority')
                        ->label('Độ ưu tiên')
                        ->numeric()
                        ->default(0)
                        ->required(),
                ])
                ->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('room.room_type_name')
                    ->label('Phòng')
                    ->searchable(),

                TextColumn::make('start_date')
                    ->label('Ngày bắt đầu')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Ngày kết thúc')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('days_of_week')
                    ->label('Ngày áp dụng')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state),

                TextColumn::make('price_modifier_type')
                    ->label('Loại thay đổi')
                    ->formatStateUsing(fn ($state) => $state === 'fixed_amount' ? 'Số tiền cố định' : 'Phần trăm'),

                TextColumn::make('price_modifier_value')
                    ->label('Giá trị thay đổi')
                    ->money('VND')
                    ->sortable(),

                TextColumn::make('priority')
                    ->label('Ưu tiên')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('price_modifier_type')
                    ->label('Loại thay đổi')
                    ->options([
                        'fixed_amount' => 'Số tiền cố định',
                        'percentage'   => 'Phần trăm',
                    ]),
            ])
            ->actions([
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
            'index'  => Pages\ListPriceRules::route('/'),
            'create' => Pages\CreatePriceRule::route('/create'),
            'edit'   => Pages\EditPriceRule::route('/{record}/edit'),
        ];
    }
}
