<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TutorResource\Pages;
use App\Models\Tutor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class TutorResource
 *
 * Filament resource class for managing Tutor models.
 * Provides form and table schemas, actions, and bulk actions for the Tutor resource.
 */
class TutorResource extends Resource
{
    /**
     * The model associated with this resource.
     */
    protected static ?string $model = Tutor::class;

    /**
     * The icon used for navigation in the Filament panel.
     */
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Define the form schema for creating or editing a tutor.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('avatar')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                    ->maxSize(2048) // 2MB
                    ->nullable(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->unique(Tutor::class, 'email')
                    ->required(),
                TextInput::make('hourly_rate')
                    ->numeric()
                    ->minValue(0)
                    ->required(),
                Textarea::make('bio')
                    ->maxLength(1000)
                    ->nullable(),
                TagsInput::make('subjects')
                    ->required()
                    ->nestedRecursiveRules([
                        'min:1',
                    ])
                    ->suggestions(Tutor::SUBJECTS),
            ]);
    }

    /**
     * Define the table schema for listing tutors.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')->circular(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('hourly_rate')->sortable(),
                TextColumn::make('subjects')->label('Subjects')->sortable()->searchable(),
                TextColumn::make('created_at')->dateTime(),
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
                    BulkAction::make('updateHourlyRates')
                        ->label('Update Hourly Rates')
                        ->action(fn (Collection $records, array $data) => self::updateHourlyRates($records, $data))
                        ->form([
                            TextInput::make('percentage')
                                ->label('Percentage Increase/Decrease')
                                ->numeric()
                                ->required()
                                ->placeholder('Enter a percentage, e.g., 10 for 10% increase or -5 for 5% decrease')
                                ->hint('Use negative values for decrease'),
                        ])
                        ->requiresConfirmation()
                        ->color('success')
                        ->icon('heroicon-o-currency-dollar'),
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
            'index' => Pages\ListTutors::route('/'),
            'create' => Pages\CreateTutor::route('/create'),
            'edit' => Pages\EditTutor::route('/{record}/edit'),
        ];
    }

    /**
     * Update the hourly rates of the selected tutors.
     *
     * Applies a percentage increase or decrease to the hourly rates of the selected tutors.
     *
     * @param  array<string, mixed>  $data
     *
     * @throws \Exception
     */
    protected static function updateHourlyRates(Collection $records, array $data): void
    {
        $percentage = $data['percentage'];

        DB::beginTransaction();

        try {
            foreach ($records as $tutor) {
                $oldRate = $tutor->hourly_rate;
                $newRate = $oldRate * (1 + $percentage / 100);

                // Ensure the new rate is not negative
                $newRate = max($newRate, 0);

                $tutor->update([
                    'hourly_rate' => $newRate,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error and re-throw to notify Filament
            Log::error('Failed to update tutor hourly rates: '.$e->getMessage());
            throw $e;
        }
    }
}
