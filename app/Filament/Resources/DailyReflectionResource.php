<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailyReflectionResource\Pages;
use App\Filament\Resources\DailyReflectionResource\RelationManagers;
use App\Models\DailyReflection;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DailyReflectionResource extends Resource
{
    protected static ?string $model = DailyReflection::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Daily Reflections';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required()->maxLength(255),
                Select::make('type')->options([
                    'quran' => "Qur'an",
                    'hadith' => 'Hadith',
                    'reflection' => 'Reflection',
                ])->required()->native(false),
                RichEditor::make('body')->required()->columnSpanFull(),
                TextInput::make('source')->maxLength(255),
                DatePicker::make('publish_date')->required()->default(now()),
                Select::make('status')->options([
                    'draft' => 'Draft',
                    'published' => 'Published',
                    'archived' => 'Archived',
                ])->default('draft')->native(false),
                Toggle::make('send_email_now')->label("Send today's reflection to members")
                    ->helperText('Optional: when status is Published. Not stored.')
                    ->visible(fn($get) => $get('status') === 'published')
                    ->dehydrated(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                BadgeColumn::make('type')->colors([
                    'primary' => 'quran',
                    'warning' => 'hadith',
                    'success' => 'reflection',
                ])->formatStateUsing(fn($state) => match($state){ 'quran'=>"Qur'an", 'hadith'=>'Hadith', default=>'Reflection' }),
                TextColumn::make('publish_date')->date()->sortable(),
                BadgeColumn::make('status')->colors([
                    'gray' => 'draft',
                    'success' => 'published',
                    'secondary' => 'archived',
                ])->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')->options([
                    'quran' => "Qur'an",
                    'hadith' => 'Hadith',
                    'reflection' => 'Reflection',
                ]),
                SelectFilter::make('status')->options([
                    'draft' => 'Draft',
                    'published' => 'Published',
                    'archived' => 'Archived',
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('publish')
                    ->visible(fn(DailyReflection $record) => $record->status !== 'published')
                    ->color('success')
                    ->form([
                        Toggle::make('email_members')->label("Send today's reflection to members")->dehydrated(false),
                    ])
                    ->action(function (DailyReflection $record, array $data) {
                        app(\App\Services\DailyReflectionService::class)->publish($record, auth()->user(), (bool)($data['email_members'] ?? false));
                    }),
                Tables\Actions\Action::make('archive')
                    ->visible(fn(DailyReflection $record) => $record->status !== 'archived')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->action(function (DailyReflection $record) {
                        app(\App\Services\DailyReflectionService::class)->archive($record, auth()->user());
                    }),
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
            'index' => Pages\ListDailyReflections::route('/'),
            'create' => Pages\CreateDailyReflection::route('/create'),
            'edit' => Pages\EditDailyReflection::route('/{record}/edit'),
        ];
    }
}
