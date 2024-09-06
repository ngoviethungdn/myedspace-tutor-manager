<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Class StudentResource
 *
 * Filament resource class for managing Student models.
 * Provides form and table schemas, actions, and bulk actions for the Student resource.
 */
class StudentResource extends Resource
{
    /**
     * The model associated with this resource.
     */
    protected static ?string $model = Student::class;

    /**
     * The icon used for navigation in the Filament panel.
     */
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Define the form schema for creating or editing a student.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Full Name'),
                TextInput::make('email')
                    ->email()
                    ->unique(Student::class, 'email')
                    ->required()
                    ->label('Email Address'),
                Select::make('grade_level')
                    ->options([
                        1 => '1',
                        2 => '2',
                        3 => '3',
                        4 => '4',
                        5 => '5',
                        6 => '6',
                        7 => '7',
                        8 => '8',
                        9 => '9',
                        10 => '10',
                        11 => '11',
                        12 => '12',
                    ])
                    ->required()
                    ->label('Grade Level'),
            ]);
    }

    /**
     * Define the table schema for listing students.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Full Name'),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->label('Email Address'),
                TextColumn::make('grade_level')
                    ->sortable()
                    ->label('Grade Level'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Created At'),
            ])
            ->filters([
                // Add custom filters here if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Get the relationships associated with this resource.
     *
     * @return array<string>
     */
    public static function getRelations(): array
    {
        return [
            // Define relationships if needed
        ];
    }

    /**
     * Get the pages associated with this resource.
     *
     * @return array<string, string>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
