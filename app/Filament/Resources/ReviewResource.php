<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder; 
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action; 
use Filament\Tables\Actions\BulkAction; 
use Illuminate\Database\Eloquent\Collection; 

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Quản lý Đánh giá';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'Đánh giá';

    protected static ?string $pluralModelLabel = 'Đánh giá khách hàng';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin đánh giá')
                    ->schema([
                        Placeholder::make('hotel_name')
                            ->label('Khách sạn được đánh giá')
                            ->content(fn (?Review $record): string => $record?->hotel?->name ?? '-'),

                        Placeholder::make('user_name')
                             ->label('Người đánh giá')
                            ->content(fn (?Review $record): string => $record?->user?->name ?? 'Khách vãng lai'), 

                        Placeholder::make('rating_display')
                            ->label('Xếp hạng')
                            ->content(fn (?Review $record): string => str_repeat('⭐', $record->rating ?? 0)), 

                        Placeholder::make('created_at_display')
                             ->label('Ngày gửi')
                            ->content(fn (?Review $record): ?string => $record?->created_at?->isoFormat('DD/MM/YYYY HH:mm')),

                        Forms\Components\TextInput::make('title')
                             ->label('Tiêu đề')
                            ->disabled() 
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('comment')
                             ->label('Nội dung bình luận gốc')
                            ->disabled()
                            ->rows(5)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Quản trị viên')
                    ->schema([
                         Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'pending' => 'Chờ duyệt',
                                'approved' => 'Đã duyệt',
                                'rejected' => 'Đã từ chối',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\Textarea::make('admin_reply')
                            ->label('Phản hồi của quản trị viên')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hotel.name')
                    ->label('Khách sạn')
                    ->searchable()
                    ->sortable()
                    ->url(fn (Review $record): ?string => $record->hotel ? HotelResource::getUrl('edit', ['record' => $record->hotel_id]) : null) 
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('user.name')
                     ->label('Người đánh giá')
                     ->placeholder('Khách vãng lai')
                    ->searchable()
                    ->sortable()
                     ->url(fn (Review $record): ?string => $record->user ? UserResource::getUrl('edit', ['record' => $record->user_id]) : null) 
                    ->openUrlInNewTab(),

                 Tables\Columns\TextColumn::make('rating')
                    ->label('Hạng')
                    ->formatStateUsing(fn (?int $state): string => $state ? str_repeat('⭐', $state) : '') 
                    ->sortable(),

                 Tables\Columns\TextColumn::make('comment')
                    ->label('Bình luận')
                    ->limit(80) 
                    ->wrap() 
                    ->tooltip(fn (?string $state): ?string => $state), 

                 Tables\Columns\BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                         'pending' => 'Chờ duyệt',
                         'approved' => 'Đã duyệt',
                         'rejected' => 'Từ chối',
                         default => $state,
                     }),

                 Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày gửi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc') 
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'pending' => 'Chờ duyệt',
                        'approved' => 'Đã duyệt',
                        'rejected' => 'Đã từ chối',
                    ]),
                 SelectFilter::make('hotel')
                    ->label('Khách sạn')
                    ->relationship('hotel', 'name')
                    ->searchable()
                    ->preload(),
                 SelectFilter::make('rating')
                     ->label('Xếp hạng')
                     ->options([
                         1 => '⭐',
                         2 => '⭐⭐',
                         3 => '⭐⭐⭐',
                         4 => '⭐⭐⭐⭐',
                         5 => '⭐⭐⭐⭐⭐',
                     ])
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Sửa/Phản hồi'), 
                Action::make('approve')
                    ->label('Duyệt')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (Review $record) => $record->update(['status' => 'approved']))
                    ->requiresConfirmation() 
                    ->visible(fn (Review $record): bool => $record->status !== 'approved'), 

                Action::make('reject')
                     ->label('Từ chối')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn (Review $record) => $record->update(['status' => 'rejected']))
                    ->requiresConfirmation()
                    ->visible(fn (Review $record): bool => $record->status !== 'rejected'),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Xóa mục đã chọn'),
                    BulkAction::make('approve')
                        ->label('Duyệt các mục đã chọn')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'approved']))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(), 
                    BulkAction::make('reject')
                         ->label('Từ chối các mục đã chọn')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'rejected']))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
             ->emptyStateActions([
                 
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
            'index' => Pages\ListReviews::route('/'),
            // 'create' => Pages\CreateReview::route('/create'), // Không cần trang tạo
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['hotel', 'user']);
    }
}
