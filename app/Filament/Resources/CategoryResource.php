<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str; 
use Filament\Forms\Set; 

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Quản lý Tin tức/Blog'; 

    protected static ?int $navigationSort = 2; 

    protected static ?string $modelLabel = 'Chuyên mục';

    protected static ?string $pluralModelLabel = 'Chuyên mục';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên chuyên mục')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true) 
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))), // Tự động tạo slug

                        Forms\Components\TextInput::make('slug')
                             ->label('Đường dẫn tĩnh (Slug)')
                            ->required()
                            ->maxLength(255)                            
                            ->unique(Category::class, 'slug', ignoreRecord: true),

                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả ngắn')
                            ->rows(3)
                            ->columnSpanFull(), 
                    ])->columns(2), 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->label('ID'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên chuyên mục')
                    ->searchable() 
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Đường dẫn tĩnh')
                    ->searchable(),
                 // Đếm số bài viết thuộc chuyên mục này
                 Tables\Columns\TextColumn::make('posts_count')
                    ->counts('posts') 
                    ->label('Số bài viết')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), 
                Tables\Columns\TextColumn::make('updated_at')
                     ->label('Cập nhật')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), 
            ])
            ->filters([
                
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            // 'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('posts');
    }
}