<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Events';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required()->maxLength(255),
                TextInput::make('slug')->required()->unique(ignoreRecord: true),
                RichEditor::make('description')->columnSpanFull(),
                TextInput::make('speaker_name')->maxLength(255),
                DateTimePicker::make('event_date')->required(),
                DateTimePicker::make('end_date'),
                Select::make('location_type')->options([
                    'online' => 'Online',
                    'in_person' => 'In Person',
                    'hybrid' => 'Hybrid',
                ])->required()->native(false),
                TextInput::make('location_detail')->maxLength(255),
                TextInput::make('cover_image')->label('Cover Image URL')->maxLength(255),
                TextInput::make('capacity')->numeric()->minValue(1)->nullable(),
                Select::make('status')->options([
                    'draft' => 'Draft','published' => 'Published','cancelled' => 'Cancelled','completed' => 'Completed'
                ])->default('draft')->native(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('event_date')->dateTime()->sortable(),
                BadgeColumn::make('status')->colors([
                    'gray' => 'draft', 'success' => 'published', 'danger' => 'cancelled', 'secondary' => 'completed',
                ])->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'draft' => 'Draft','published' => 'Published','cancelled' => 'Cancelled','completed' => 'Completed'
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('publish')
                    ->visible(fn(Event $record): bool => $record->status !== 'published')
                    ->color('success')
                    ->action(fn($record) => app(\App\Services\EventService::class)->publish($record, auth()->user())),
                Tables\Actions\Action::make('cancel')
                    ->visible(fn(Event $record): bool => $record->status !== 'cancelled')
                    ->requiresConfirmation()->color('danger')
                    ->action(fn($record) => app(\App\Services\EventService::class)->cancel($record, auth()->user())),
                Tables\Actions\Action::make('complete')
                    ->visible(fn(Event $record): bool => $record->status !== 'completed')
                    ->color('secondary')
                    ->action(fn($record) => app(\App\Services\EventService::class)->complete($record, auth()->user())),
                Tables\Actions\Action::make('exportCsv')->label('Export RSVPs CSV')->icon('heroicon-o-arrow-down-tray')
                    ->action(function($record){
                        $filename = 'tmc-rsvps-'.$record->id.'.csv';
                        $headers = [
                          'Content-Type' => 'text/csv',
                          'Content-Disposition' => 'attachment; filename='.$filename,
                        ];
                        $callback = function() use ($record){
                          $out = fopen('php://output', 'w');
                          fputcsv($out, ['Name','Email','Status','Registered At']);
                          foreach ($record->rsvps()->with('user')->get() as $r) {
                              fputcsv($out, [optional($r->user)->name, optional($r->user)->email, $r->status, optional($r->registered_at)->toDateTimeString()]);
                          }
                          fclose($out);
                        };
                        return response()->stream($callback, 200, $headers);
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
