<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str; 
use Filament\Forms\Set;    
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;


class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Quản lý Tin tức/Blog';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Bài viết';

    protected static ?string $pluralModelLabel = 'Bài viết';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)->schema([
                    Section::make('Nội dung chính')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Tiêu đề bài viết')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                            Forms\Components\TextInput::make('slug')
                                ->label('Đường dẫn tĩnh (Slug)')
                                ->required()
                                ->unique(Post::class, 'slug', ignoreRecord: true)
                                ->maxLength(255),

                            Forms\Components\RichEditor::make('content')
                                ->label('Nội dung chi tiết')
                                ->required()
                                ->fileAttachmentsDisk('public')
                                ->fileAttachmentsDirectory('post-content-attachments')
                                ->columnSpanFull(),
                        ])->columnSpan(2),

                    Section::make('Thông tin phụ')
                        ->schema([
                            Forms\Components\FileUpload::make('image')
                                ->label('Ảnh đại diện')
                                ->disk('public')
                                ->directory('posts')
                                ->image()
                                ->imageEditor()
                                ->imagePreviewHeight('150')
                                ->loadingIndicatorPosition('left')
                                ->panelAspectRatio('2:1')
                                ->panelLayout('integrated')
                                ->removeUploadedFileButtonPosition('right')
                                ->uploadButtonPosition('left')
                                ->uploadProgressIndicatorPosition('left'),

                             Forms\Components\Select::make('category_id')
                                ->label('Chuyên mục')
                                ->relationship('category', 'name')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->native(false),

                             Forms\Components\Select::make('status')
                                ->label('Trạng thái')
                                ->options([
                                    'draft' => 'Bản nháp',
                                    'published' => 'Đã xuất bản',
                                    'archived' => 'Đã lưu trữ',
                                ])
                                ->required()
                                ->default('draft')
                                ->native(false),

                             Forms\Components\DateTimePicker::make('published_at')
                                ->label('Ngày xuất bản')
                                ->native(false)
                                ->default(now())
                                ->helperText('Nếu đặt ngày trong tương lai, bài viết sẽ được lên lịch.'),

                        ])->columnSpan(1),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Ảnh')
                    ->disk('public')
                    ->square(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->limit(50),


                Tables\Columns\TextColumn::make('category.name')
                     ->label('Chuyên mục')
                    ->searchable()
                    ->sortable()
                    ->badge(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->colors([
                        'primary',
                        'success' => 'published',
                        'warning' => 'draft',
                        'danger' => 'archived',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                         'draft' => 'Bản nháp',
                         'published' => 'Xuất bản',
                         'archived' => 'Lưu trữ',
                         default => $state,
                     }),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Ngày xuất bản')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('category')
                    ->label('Lọc theo chuyên mục')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                 SelectFilter::make('status')
                     ->label('Lọc theo trạng thái')
                    ->options([
                        'draft' => 'Bản nháp',
                        'published' => 'Đã xuất bản',
                        'archived' => 'Đã lưu trữ',
                    ]),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            // 'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['category']); 
    }
}