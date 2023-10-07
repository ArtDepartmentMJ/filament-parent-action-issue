<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->action(
                        \Filament\Tables\Actions\Action::make('compare_value')
                            ->form([
                                TextInput::make('new_value')
                                    ->label('New value')
                                    ->hintAction(
                                        \Filament\Forms\Components\Actions\Action::make('compare_new_value')
                                            ->action(function (Model $record, Get $get) {
                                                $record->name = $get('new_value');
                                                $record->save();
                                            })->label('Use new value')
                                            ->cancelParentActions('compare_value')
                                    ),
                                TextInput::make('old_value')
                                    ->label('Old value')
                                    ->hintAction(
                                        \Filament\Forms\Components\Actions\Action::make('compare_old_value')
                                            ->action(function (Model $record, Get $get) {
                                                $record->name = $get('old_value');
                                                $record->save();
                                            })->label('Use old value')
                                            ->cancelParentActions('compare_value')
                                    ),
                            ])
                            ->fillForm(function (Model $record) {
                                return [
                                    'new_value' => 'new val',
                                    'old_value' => $record->name,
                                ];
                            })
                            ->modalWidth('sm')
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false)
                    ),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
