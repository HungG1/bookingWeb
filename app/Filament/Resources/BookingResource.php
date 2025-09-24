<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers\PaymentHistoriesRelationManager;
use App\Models\Booking;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput, DatePicker, Select, Textarea, Section};
use Filament\Tables\Columns\{
    TextColumn, BadgeColumn
};

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Quản lý Đặt phòng'; 

    protected static ?int $navigationSort = 6;
    
    protected static ?string $navigationLabel = 'Đặt phòng';
    
    protected static ?string $modelLabel = 'Booking';
    
    protected static ?string $pluralModelLabel = 'Danh sách Booking';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Section Thông tin khách hàng
                Section::make('Thông tin khách hàng')
                    ->schema([
                        Select::make('user_id')
                            ->label('Người dùng')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->nullable()
                            ->helperText('Chọn nếu khách hàng đã đăng ký; nếu không, điền thông tin phía dưới.'),
                            
                        TextInput::make('customer_name')
                            ->label('Tên khách hàng')
                            ->required()
                            ->maxLength(255),
                            
                        TextInput::make('customer_email')
                            ->label('Email khách hàng')
                            ->email()
                            ->required()
                            ->maxLength(255),
                            
                        TextInput::make('customer_phone')
                            ->label('Số điện thoại')
                            ->required()
                            ->maxLength(50),
                            
                        Textarea::make('customer_notes')
                            ->label('Ghi chú khách hàng')
                            ->rows(3)
                            ->nullable(),
                    ])->columns(2),

                // Section Thông tin đặt phòng
                Section::make('Thông tin đặt phòng')
                    ->schema([
                        Select::make('hotel_id')
                            ->label('Khách sạn')
                            ->relationship('hotel', 'name')
                            ->required()
                            ->searchable(),
                            
                        Select::make('room_id')
                            ->label('Phòng')
                            ->relationship('room', 'room_type_name')
                            ->required()
                            ->searchable(),
                            
                        DatePicker::make('check_in_date')
                            ->label('Ngày nhận phòng')
                            ->required(),
                            
                        DatePicker::make('check_out_date')
                            ->label('Ngày trả phòng')
                            ->required(),
                            
                        TextInput::make('num_adults')
                            ->label('Số người lớn')
                            ->numeric()
                            ->default(1)
                            ->required(),
                            
                        TextInput::make('num_children')
                            ->label('Số trẻ em')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])->columns(2),

                // Section Thông tin giá
                Section::make('Thông tin giá')
                    ->schema([
                        TextInput::make('base_price')
                            ->label('Giá gốc')
                            ->numeric()
                            ->required()
                            ->helperText('Giá phòng ban đầu'),
                            
                        TextInput::make('discount_amount')
                            ->label('Giảm giá')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Số tiền giảm giá (nếu có)'),
                            
                        TextInput::make('final_price')
                            ->label('Giá cuối cùng')
                            ->numeric()
                            ->required()
                            ->helperText('Giá sau khi trừ giảm giá'),
                    ])->columns(3),

                // Section Trạng thái đặt phòng & thanh toán
                Section::make('Trạng thái đặt phòng & thanh toán')
                    ->schema([
                        Select::make('status')
                            ->label('Trạng thái đặt phòng')
                            ->required()
                            ->options([
                                'pending'            => 'Chờ duyệt',
                                'confirmed'          => 'Đã duyệt',
                                'cancelled_by_user'  => 'Hủy bởi khách hàng',
                                'cancelled_by_admin' => 'Hủy bởi admin',
                                'checked_in'         => 'Đã nhận phòng',
                                'checked_out'        => 'Trả phòng',
                                'no_show'            => 'Không đến',
                            ]),
                            
                        Select::make('payment_status')
                            ->label('Trạng thái thanh toán')
                            ->required()
                            ->options([
                                'unpaid'         => 'Chưa thanh toán',
                                'paid'           => 'Đã thanh toán',
                                'partially_paid' => 'Thanh toán một phần',
                                'refunded'       => 'Đã hoàn tiền',
                                'payment_failed' => 'Thanh toán thất bại',
                            ]),
                            
                        TextInput::make('payment_method')
                            ->label('Phương thức thanh toán')
                            ->maxLength(100)
                            ->nullable(),
                            
                        TextInput::make('transaction_id')
                            ->label('Mã giao dịch')
                            ->maxLength(100)
                            ->nullable(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
       return $table
          ->columns([
              TextColumn::make('id')->sortable(),
              TextColumn::make('customer_name')
                  ->label('Tên khách hàng')
                  ->searchable()
                  ->sortable(),
              TextColumn::make('hotel.name')
                  ->label('Khách sạn')
                  ->searchable()
                  ->sortable(),
              TextColumn::make('room.room_type_name')
                  ->label('Loại phòng')
                  ->searchable()
                  ->sortable(),
              TextColumn::make('check_in_date')
                  ->label('Nhận phòng')
                  ->date('d/m/Y')
                  ->sortable(),
              TextColumn::make('check_out_date')
                  ->label('Trả phòng')
                  ->date('d/m/Y')
                  ->sortable(),
              TextColumn::make('final_price')
                  ->label('Giá cuối cùng')
                  ->money('VND')
                  ->sortable(),
              BadgeColumn::make('status')
                  ->label('Trạng thái')
                  ->colors([
                      'warning'   => 'pending',
                      'success'   => 'confirmed',
                      'danger'    => fn ($state): bool => in_array($state, ['cancelled_by_user', 'cancelled_by_admin']),
                      'secondary' => fn ($state): bool => in_array($state, ['checked_in', 'checked_out', 'no_show']),
                  ])
                  ->sortable(),
              BadgeColumn::make('payment_status')
                  ->label('TT')
                  ->colors([
                      'danger'    => 'payment_failed',
                      'primary'   => 'paid',
                      'secondary' => 'unpaid',
                      'warning'   => 'partially_paid',
                      'success'   => 'refunded',
                  ])
                  ->sortable(),
              TextColumn::make('created_at')
                  ->label('Ngày tạo')
                  ->dateTime('d/m/Y H:i')
                  ->sortable()
                  ->toggleable(isToggledHiddenByDefault: true),
          ])
          ->filters([
              Tables\Filters\SelectFilter::make('status')
                  ->label('Trạng thái đặt phòng')
                  ->options([
                      'pending'            => 'Chờ duyệt',
                      'confirmed'          => 'Đã duyệt',
                      'cancelled_by_user'  => 'Hủy bởi khách hàng',
                      'cancelled_by_admin' => 'Hủy bởi admin',
                      'checked_in'         => 'Đã nhận phòng',
                      'checked_out'        => 'Trả phòng',
                      'no_show'            => 'Không đến',
                  ]),
              Tables\Filters\SelectFilter::make('payment_status')
                  ->label('Trạng thái thanh toán')
                  ->options([
                      'unpaid'         => 'Chưa thanh toán',
                      'paid'           => 'Đã thanh toán',
                      'partially_paid' => 'Thanh toán 1 phần',
                      'refunded'       => 'Đã hoàn tiền',
                      'payment_failed' => 'Thanh toán thất bại',
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

    public static function getRelations(): array
    {
       return [
           PaymentHistoriesRelationManager::class,
       ];
    }

    public static function getPages(): array
    {
       return [
           'index'  => Pages\ListBookings::route('/'),
           'create' => Pages\CreateBooking::route('/create'),
           'edit'   => Pages\EditBooking::route('/{record}/edit'),
       ];
    }
}
