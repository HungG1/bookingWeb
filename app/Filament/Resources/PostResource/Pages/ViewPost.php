<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Image;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Support\Enums\FontWeight;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('category.name')
                    ->label('Danh mục'),
                Placeholder::make('author.name')
                    ->label('Tác giả'),
                Placeholder::make('title')
                    ->label('Tiêu đề')
                    ->weight(FontWeight::Bold)
                    ->size('xl'),
                Placeholder::make('slug')
                    ->label('Slug')
                    ->helperText('Đường dẫn tĩnh của bài viết'),
                Placeholder::make('content')
                    ->label('Nội dung')
                    ->content($this->record->content) // Hiển thị nội dung HTML
                    ->columnSpanFull(),
                Placeholder::make('image')
                    ->label('Ảnh đại diện')
                    ->content(fn () => $this->record->image ? '<img src="' . asset('storage/' . $this->record->image) . '" class="rounded-lg" />' : 'Không có ảnh')
                    ->columnSpanFull(),
                Placeholder::make('status')
                    ->label('Trạng thái'),
                Placeholder::make('published_at')
                    ->label('Ngày xuất bản')
                    ->dateTime('d/m/Y H:i'),
                Placeholder::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i'),
                Placeholder::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}