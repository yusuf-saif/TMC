<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Filament\Resources\AnnouncementResource\RelationManagers;
use App\Models\Announcement;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'Announcements';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required()->maxLength(255),
                TextInput::make('slug')->required()->unique(ignoreRecord: true),
                RichEditor::make('body')->required()->columnSpanFull(),
                Select::make('status')->options([
                    'draft' => 'Draft',
                    'scheduled' => 'Scheduled',
                    'published' => 'Published',
                    'archived' => 'Archived',
                ])->default('draft')->native(false),
                DateTimePicker::make('publish_at')->label('Publish At')->seconds(false),
                Toggle::make('send_email')->label('Send Email To Members'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                BadgeColumn::make('status')->colors([
                    'gray' => 'draft','warning' => 'scheduled','success' => 'published','secondary' => 'archived'
                ])->sortable(),
                TextColumn::make('publish_at')->dateTime()->sortable(),
                TextColumn::make('published_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'draft' => 'Draft','scheduled' => 'Scheduled','published' => 'Published','archived' => 'Archived'
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('publishNow')->label('Publish Now')
                    ->visible(fn(Announcement $record): bool => $record->status !== 'published')
                    ->color('success')
                    ->action(fn($record)=>app(\App\Services\AnnouncementService::class)->publishNow($record, auth()->user())),
                Tables\Actions\Action::make('archive')
                    ->visible(fn(Announcement $record): bool => $record->status !== 'archived')
                    ->color('gray')->requiresConfirmation()
                    ->action(fn($record)=>app(\App\Services\AnnouncementService::class)->archive($record, auth()->user())),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
