<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class PaymentHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'paymentHistories';

    protected static ?string $title = 'Lịch sử thanh toán';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Số tiền')
                    ->money('VND')
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('Phương thức')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('transaction_id')
                    ->label('Mã giao dịch')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->colors([
                        'success' => 'success',
                        'danger' => 'failed',
                        'warning' => 'pending',
                    ])
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'pending' => 'Chờ xử lý',
                        'success' => 'Thành công',
                        'failed' => 'Thất bại',
                    ]),
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }
}
