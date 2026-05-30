<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembersResource\Pages;
use App\Models\User;
use App\Services\MembershipService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class MembersResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Members';
    protected static ?string $modelLabel = 'Member';
    protected static ?string $pluralModelLabel = 'Members';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('membership_number')->disabled(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'suspended' => 'Suspended',
                    ])->required(),
                Forms\Components\Textarea::make('rejection_reason')->label('Rejection Reason')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'warning' => 'pending',
                    'success' => 'approved',
                    'danger' => 'rejected',
                    'gray' => 'suspended',
                ])->sortable(),
                Tables\Columns\TextColumn::make('membership_number')->label('Member #')->sortable(),
                Tables\Columns\TextColumn::make('approved_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    'suspended' => 'Suspended',
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->visible(fn(User $record) => $record->status !== 'approved')
                    ->requiresConfirmation()
                    ->color('success')
                    ->action(function (User $record) {
                        app(MembershipService::class)->approve($record, auth()->user());
                    }),
                Tables\Actions\Action::make('reject')
                    ->visible(fn(User $record) => $record->status !== 'rejected')
                    ->color('danger')
                    ->form([
                        Forms\Components\Textarea::make('reason')->label('Reason')->required(),
                    ])
                    ->action(function (User $record, array $data) {
                        app(MembershipService::class)->reject($record, auth()->user(), $data['reason']);
                    }),
                Tables\Actions\Action::make('suspend')
                    ->visible(fn(User $record) => $record->status !== 'suspended')
                    ->color('gray')
                    ->form([
                        Forms\Components\Textarea::make('reason')->label('Reason')->nullable(),
                    ])
                    ->action(function (User $record, array $data) {
                        app(MembershipService::class)->suspend($record, auth()->user(), $data['reason'] ?? null);
                    }),
                Tables\Actions\Action::make('reactivate')
                    ->visible(fn(User $record) => in_array($record->status, ['rejected', 'suspended']))
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        app(MembershipService::class)->reactivate($record, auth()->user());
                    }),
                Tables\Actions\EditAction::make()->visible(false),
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
            'index' => Pages\ListMembers::route('/'),
        ];
    }
}
