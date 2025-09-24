<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter; 
use Filament\Forms\Components\Section; 
use Filament\Pages\Page; 

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users'; 

    protected static ?string $navigationGroup = 'Quản lý Người dùng & Quyền'; 

    protected static ?int $navigationSort = 1; 

    protected static ?string $modelLabel = 'Người dùng'; 

    protected static ?string $pluralModelLabel = 'Người dùng'; 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin cơ bản')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Họ và tên')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Địa chỉ Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone_number')
                            ->label('Số điện thoại')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\Textarea::make('address')
                            ->label('Địa chỉ')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('password')
                            ->label('Mật khẩu')
                            ->password()
                            ->revealable()
                            ->required(fn (Page $livewire): bool => $livewire instanceof Pages\CreateUser)
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->maxLength(255),
                         // (Tùy chọn) Thêm trường xác nhận mật khẩu khi tạo mới
                        Forms\Components\TextInput::make('password_confirmation')
                             ->label('Xác nhận mật khẩu')
                             ->password()
                             ->revealable()
                             ->required(fn (Page $livewire): bool => $livewire instanceof Pages\CreateUser)
                             ->same('password') 
                             ->dehydrated(false),
                        // Hiển thị trạng thái xác thực (chỉ đọc) khi sửa
                        Forms\Components\Placeholder::make('email_verified_at_display')
                            ->label('Trạng thái xác thực Email')
                            ->content(fn (?User $record): string => $record?->email_verified_at ? $record->email_verified_at->isoFormat('DD/MM/YYYY HH:mm') : 'Chưa xác thực')
                            ->visibleOn('edit'), // Vẫn chỉ hiển thị khi sửa
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->label('ID'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Họ và tên')
                    ->searchable(), 
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(), 
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Số điện thoại')
                    ->placeholder('-'), 
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Đã xác thực')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày đăng ký')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), 
                Tables\Columns\TextColumn::make('updated_at')
                     ->label('Cập nhật lần cuối')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), 
            ])
            ->filters([
                // Bộ lọc cho người dùng đã xác thực / chưa xác thực
                Filter::make('verified')
                    ->label('Đã xác thực Email')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
                Filter::make('unverified')
                    ->label('Chưa xác thực Email')
                    ->query(fn (Builder $query): Builder => $query->whereNull('email_verified_at')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Xem'), 
                Tables\Actions\EditAction::make()->label('Sửa'),
                Tables\Actions\DeleteAction::make()->label('Xóa'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Xóa mục đã chọn'),
                ]),
            ])
            ->emptyStateActions([ 
                Tables\Actions\CreateAction::make()->label('Tạo người dùng mới'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
             // 'view' => Pages\ViewUser::route('/{record}'), // Dùng ViewAction modal thay thế thường tiện hơn
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

}