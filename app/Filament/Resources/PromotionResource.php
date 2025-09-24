<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionResource\Pages;
use App\Models\Promotion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Get; 
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action; 
use Filament\Tables\Actions\BulkAction; 
use Illuminate\Database\Eloquent\Collection; 

class PromotionResource extends Resource
{
    protected static ?string $model = Promotion::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationGroup = 'Quản lý Giá & Khuyến mãi'; 

    protected static ?int $navigationSort = 9;

    protected static ?string $modelLabel = 'Khuyến mãi';

    protected static ?string $pluralModelLabel = 'Khuyến mãi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin cơ bản')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên chương trình khuyến mãi')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2), 

                        Forms\Components\Toggle::make('is_active')
                             ->label('Kích hoạt')
                            ->default(true),

                        Forms\Components\TextInput::make('code')
                             ->label('Mã giảm giá (Coupon)')
                            ->helperText('Để trống nếu chương trình áp dụng tự động (không cần mã). Mã phải là duy nhất.')
                            ->maxLength(50)
                            ->unique(Promotion::class, 'code', ignoreRecord: true) 
                            ->columnSpan(1), 

                        Forms\Components\FileUpload::make('image')
                            ->label('Ảnh đại diện')
                            ->image() // Chỉ định đây là upload ảnh (có preview)
                            ->disk('public') // Quan trọng: Chỉ định disk lưu trữ (thường là 'public')
                            ->directory('promotions') // Thư mục con trong disk để lưu ảnh khuyến mãi
                            ->visibility('public') // Đảm bảo file có thể truy cập công khai
                            ->columnSpanFull(), // Cho chiếm hết chiều rộng

                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả chi tiết')
                            ->rows(3)
                            ->columnSpanFull(), 
                    ])->columns(3),

                Section::make('Chi tiết giảm giá')
                    ->schema([
                         Forms\Components\Select::make('discount_type')
                             ->label('Loại giảm giá')
                            ->options([
                                'fixed_amount' => 'Số tiền cố định',
                                'percentage' => 'Tỷ lệ phần trăm',
                            ])
                            ->required()
                            ->live() 
                            ->native(false),

                         Forms\Components\TextInput::make('discount_value')
                            ->label('Giá trị giảm')
                            ->required()
                            ->numeric()
                            ->prefix(fn (Get $get): ?string => ($get('discount_type') === 'fixed_amount' ? '₫' : null))
                            ->suffix(fn (Get $get): ?string => ($get('discount_type') === 'percentage' ? '%' : null)),
                    ])->columns(2),

                Section::make('Điều kiện áp dụng')
                    ->schema([
                        Forms\Components\TextInput::make('min_spend')
                            ->label('Giá trị đơn hàng tối thiểu (VND)')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('₫')
                            ->placeholder('Không yêu cầu'),

                        Forms\Components\DateTimePicker::make('start_date')
                             ->label('Ngày giờ bắt đầu')
                            ->helperText('Để trống nếu bắt đầu ngay lập tức.')
                            ->native(false),

                        Forms\Components\DateTimePicker::make('end_date')
                            ->label('Ngày giờ kết thúc')
                             ->helperText('Để trống nếu không có ngày hết hạn.')
                            ->afterOrEqual('start_date') 
                            ->native(false),
                    ])->columns(3),

                Section::make('Giới hạn sử dụng')
                    ->schema([
                        Forms\Components\TextInput::make('usage_limit')
                            ->label('Tổng số lượt sử dụng tối đa')
                            ->integer()
                            ->minValue(0)
                            ->placeholder('Không giới hạn'),

                         Forms\Components\TextInput::make('used_count')
                             ->label('Số lượt đã sử dụng')
                            ->integer()
                            ->default(0)
                            ->readOnly() 
                            ->disabled(), 
                    ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Ảnh')
                    ->disk('public') // Chỉ định disk nơi lưu ảnh
                    ->visibility('public') // Đảm bảo có thể xem ảnh public
                    ->width(60) // Điều chỉnh kích thước nếu cần
                    ->height(60)
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Tên chương trình')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->tooltip(fn (?string $state) => $state),

                Tables\Columns\TextColumn::make('code')
                     ->label('Mã Code')
                    ->searchable()
                    ->copyable() 
                    ->copyMessage('Đã sao chép mã!')
                    ->placeholder('-'),

                 Tables\Columns\TextColumn::make('discount_value')
                    ->label('Giá trị giảm')
                    // Định dạng dựa trên loại
                    ->formatStateUsing(function ($state, Promotion $record): string {
                        if ($record->discount_type === 'percentage') {
                            return $state . '%';
                        } elseif ($record->discount_type === 'fixed_amount') {
                            return number_format($state, 0, ',', '.') . ' ₫';
                        }
                        return (string) $state;
                    })
                    ->alignRight(),

                 Tables\Columns\TextColumn::make('min_spend')
                     ->label('Đơn tối thiểu')
                    ->formatStateUsing(fn (?string $state): string => $state ? number_format($state, 0, ',', '.') . ' ₫' : '-')
                    ->sortable()
                    ->alignRight(),

                 Tables\Columns\TextColumn::make('used_count')
                    ->label('Sử dụng')
                     // Hiển thị dạng "đã dùng / giới hạn"
                    ->formatStateUsing(fn ($state, Promotion $record) => $state . ' / ' . ($record->usage_limit ?? '∞'))
                    ->alignCenter(),

                 Tables\Columns\TextColumn::make('start_date')
                    ->label('Bắt đầu')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Luôn bắt đầu')
                    ->sortable(),

                 Tables\Columns\TextColumn::make('end_date')
                    ->label('Kết thúc')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Không hết hạn')
                    ->sortable(),

                 Tables\Columns\IconColumn::make('is_active')
                     ->label('Kích hoạt')
                    ->boolean(),

                 Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Trạng thái kích hoạt')
                    ->boolean()
                    ->trueLabel('Đang kích hoạt')
                    ->falseLabel('Không kích hoạt'),
                SelectFilter::make('discount_type')
                     ->label('Loại giảm giá')
                    ->options([
                        'fixed_amount' => 'Số tiền cố định',
                        'percentage' => 'Tỷ lệ phần trăm',
                    ]),

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                 Action::make('toggleActive')
                    ->label(fn (Promotion $record): string => $record->is_active ? 'Hủy kích hoạt' : 'Kích hoạt')
                    ->icon(fn (Promotion $record): string => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (Promotion $record): string => $record->is_active ? 'danger' : 'success')
                    ->action(fn (Promotion $record) => $record->update(['is_active' => !$record->is_active]))
                    ->requiresConfirmation(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Xóa mục đã chọn'),
                     BulkAction::make('activate')
                        ->label('Kích hoạt mục đã chọn')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => true]))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                     BulkAction::make('deactivate')
                         ->label('Hủy kích hoạt mục đã chọn')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => false]))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListPromotions::route('/'),
            'create' => Pages\CreatePromotion::route('/create'),
            // 'view' => Pages\ViewPromotion::route('/{record}'),
            'edit' => Pages\EditPromotion::route('/{record}/edit'),
        ];
    }
}
