<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AmenityResource\Pages;
use App\Models\Amenity;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput, Section};
use Filament\Tables\Columns\{TextColumn};
use Illuminate\Support\HtmlString; // Thêm import này
use Illuminate\Support\Str; // Thêm import này

class AmenityResource extends Resource
{
    protected static ?string $model = Amenity::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationGroup = 'Quản lý Khách sạn & Phòng';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Tiện ích';

    protected static ?string $modelLabel = 'Tiện ích';

    protected static ?string $pluralModelLabel = 'Danh sách tiện ích';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin tiện ích')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên tiện ích')
                            ->required()
                            ->maxLength(255)
                            ->unique(Amenity::class, 'name', ignoreRecord: true)
                            ->helperText('Tên của tiện ích phải là duy nhất.'),

                        TextInput::make('icon')
                            ->label('Icon')
                            ->placeholder("Ví dụ: fa fa-wifi hoặc heroicon-o-wifi")
                            ->maxLength(255)
                            ->helperText('Nhập tên class icon (FontAwesome, Bootstrap Icons...) hoặc tên Heroicon.'), // Cập nhật helper text rõ ràng hơn
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
                    ->label('Tên tiện ích')
                    ->searchable()
                    ->sortable(),

                // --- Cập nhật cột Icon ---
                TextColumn::make('icon')
                    ->label('Icon')
                    ->searchable() // Vẫn cho phép tìm kiếm theo tên class/tên icon
                    ->html() // Cho phép cột render HTML
                    ->formatStateUsing(function (?string $state): HtmlString {
                        if (blank($state)) {
                            return new HtmlString(''); // Trả về chuỗi rỗng nếu không có icon
                        }

                        // Kiểm tra xem có phải là Heroicon không (dựa vào prefix 'heroicon-')
                        if (Str::startsWith($state, 'heroicon-')) {
                            // Sử dụng helper 'svg' của Filament để render Heroicon
                            // Bạn có thể tùy chỉnh kích thước (vd: 'h-5 w-5', 'h-6 w-6')
                            try {
                                return new HtmlString(svg($state, 'h-5 w-5 text-gray-500')->toHtml());
                            } catch (\Exception $e) {
                                // Trường hợp tên heroicon không hợp lệ
                                return new HtmlString('<span class="text-red-500 text-xs">Invalid Icon</span>');
                            }
                        }

                        return new HtmlString('<i class="' . e($state) . ' text-xl text-red-500"></i>');
                    }),
                // --- Kết thúc cập nhật cột Icon ---

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([ // Nên nhóm các bulk action lại
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAmenities::route('/'),
            'create' => Pages\CreateAmenity::route('/create'),
            'edit'   => Pages\EditAmenity::route('/{record}/edit'),
            // 'view' => Pages\ViewAmenity::route('/{record}'), // Nếu cần trang view chi tiết
        ];
    }
}