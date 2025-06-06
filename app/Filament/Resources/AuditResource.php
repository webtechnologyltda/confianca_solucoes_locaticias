<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditResource\Pages;
use App\Models\Audit;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use ValentinMorice\FilamentJsonColumn\JsonColumn;

class AuditResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Audit::class;

    protected static ?string $navigationIcon = 'fluentui-slide-search-28';

    protected static ?string $navigationGroup = 'Administrativo';

    protected static ?string $navigationLabel = 'Auditoria';

    protected static ?string $label = 'Registro de Auditoria';

    protected static ?string $pluralLabel = 'Registros de Auditoria';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->columns([
                        'sm' => 1,
                        'lg' => 2,
                    ])
                    ->schema([
                        Forms\Components\Hidden::make('user_id'),
                        Forms\Components\Hidden::make('user_type'),
                        Forms\Components\TextInput::make('username')
                            ->afterStateHydrated(function (Forms\Get $get, Forms\Set $set) {
                                if (! empty($get('user_type')) && ! empty($get('user_id'))) {
                                    $set('username', $get('user_type')::find($get('user_id'))?->name);
                                } else {
                                    $set('username', 'Sem Usuário Definido');
                                }
                            })
                            ->label('Usuário')
                            ->placeholder('Sem Usuário Definido')
                            ->columnSpanFull(),

                        Forms\Components\ToggleButtons::make('event')
                            ->label('Evento')
                            ->columnSpanFull()
                            ->inline()
                            ->colors([
                                'created' => 'info',
                                'updated' => 'warning',
                                'deleted' => 'danger',
                                'default' => 'secondary',
                            ])
                            ->options([
                                'created' => 'Criou',
                                'updated' => 'Atualizou',
                                'deleted' => 'Deletou',
                            ]),

                        Forms\Components\TextInput::make('auditable_type')
                            ->label('Modelo')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('auditable_id')
                            ->label('ID do Modelo')
                            ->required()
                            ->numeric(),

                        JsonColumn::make('old_values')
                            ->hidden(fn (Forms\Get $get) => $get('event') === 'created')
                            ->label('Valores Antigos')
                            ->viewerOnly()
                            ->columnSpanFull(),

                        JsonColumn::make('new_values')
                            ->label('Valores Novos')
                            ->hidden(fn (Forms\Get $get) => $get('event') === 'deleted')
                            ->viewerOnly()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('url')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('ip_address')
                            ->label('IP')
                            ->maxLength(45),

                        Forms\Components\Fieldset::make()
                            ->columnSpan([
                                'sm' => 1,
                                'lg' => 1,
                            ])
                            ->label('Dispositivo')
                            ->schema([
                                Forms\Components\Placeholder::make('user_agent')
                                    ->columnSpanFull()
                                    ->hiddenLabel()
                                    ->content(function ($state, Forms\Set $set) {
                                        $agent = self::parseUserAgent($state);

                                        return new HtmlString('<div class="flex flex-col items-center">
                                            <span>'.$agent['tipo_dispositivo'].'</span>
                                            <span class="flex flex-row justify-center items-center"> '.$agent['sistema_operacional'].'</span>
                                            <span class="flex flex-row justify-center items-center">'.$agent['navegador'].'</span>
                                        </div>');
                                    }),
                            ]),

                        Forms\Components\TagsInput::make('tags')
                            ->placeholder('Nenhuma tag informada!'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuário')
                    ->default('Sem Usuário Definido')
                    ->sortable(),
                Tables\Columns\TextColumn::make('event')
                    ->label('Evento')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'created' => 'Criou',
                            'updated' => 'Atualizou',
                            'deleted' => 'Deletou',
                        };
                    })
                    ->color(function ($state) {
                        return match ($state) {
                            'created' => 'info',
                            'updated' => 'warning',
                            'deleted' => 'danger',
                            default => 'secondary',
                        };
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('auditable_type')
                    ->label('Modelo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('auditable_id')
                    ->label('Id do Modelo')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_agent')
                    ->color('gray')
                    ->alignCenter()
                    ->label('Dispositivo')
                    ->formatStateUsing(function ($state) {
                        $agent = self::parseUserAgent($state);

                        return new HtmlString('<div class="flex flex-col">
                                <span>'.$agent['tipo_dispositivo'].'</span>
                                <span class="flex flex-row justify-center items-center"> '.$agent['sistema_operacional'].'</span>
                                <span class="flex flex-row justify-center items-center">'.$agent['navegador'].'</span>
                            </div>');
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('tags')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data do Registro')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->poll('30s')
            ->deferFilters()
            ->persistFiltersInSession()
            ->striped()
            ->deferLoading()
            ->defaultSort('id', 'desc')
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContent)
            ->filters([
                SelectFilter::make('event')
                    ->searchable()
                    ->multiple()
                    ->label('Evento')
                    ->preload()
                    ->options([
                        'created' => 'Criou',
                        'updated' => 'Atualizou',
                        'deleted' => 'Deletou',
                    ]),

                SelectFilter::make('auditable_type')
                    ->searchable()
                    ->multiple()
                    ->preload()
                    ->options(Audit::select('auditable_type')->distinct()->pluck('auditable_type', 'auditable_type'))
                    ->label('Modelo'),

                SelectFilter::make('user_id')
                    ->searchable()
                    ->multiple()
                    ->preload()
                    ->options(User::pluck('name', 'id'))
                    ->label('Usuário'),

                Filter::make('created_at')
                    ->columns(2)
                    ->columnSpan([
                        'sm' => 1,
                        'lg' => 2,
                    ])
                    ->form([
                        Forms\Components\Fieldset::make('Data de Criação')
                            ->columnSpan(2)
                            ->schema([
                                DatePicker::make('created_from')
                                    ->date('d/m/Y')
                                    ->displayFormat('d/m/Y')
                                    ->native(false)
                                    ->label('A partir de'),
                                DatePicker::make('created_until')
                                    ->date('d/m/Y')
                                    ->displayFormat('d/m/Y')
                                    ->native(false)
                                    ->label('Até'),
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
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
            'index' => Pages\ListAudits::route('/'),
            'view' => Pages\ViewAudit::route('/{record}'),
        ];
    }

    private static function parseUserAgent($userAgent)
    {
        $os = 'Desconhecido';
        $browser = 'Desconhecido';
        $deviceType = '💻 Computador';

        // Detecta sistema operacional
        if (preg_match('/Android/i', $userAgent)) {
            $os = Blade::render('<x-eos-android class="w-4 h-4 text-lime-400 mr-1.5" />').' Android';
            $deviceType = '📱 Celular';
        } elseif (preg_match('/iPhone/i', $userAgent)) {
            $os = Blade::render('<x-bi-apple class="w-4 h-4 text-gray-200 mr-1.5" />').' iOS';
            $deviceType = '📱 iPhone';
        } elseif (preg_match('/iPad/i', $userAgent)) {
            $os = Blade::render('<x-bi-apple class="w-4 h-4 text-gray-200 mr-1.5" />').' iOS';
            $deviceType = '📱 iPad';
        } elseif (preg_match('/iPod/i', $userAgent)) {
            $os = Blade::render('<x-bi-apple class="w-4 h-4 text-gray-200 mr-1.5" />').' iOS';
            $deviceType = 'iPod';
        } elseif (preg_match('/Windows Phone/i', $userAgent)) {
            $os = Blade::render('<x-bi-windows class="w-4 h-4 text-cyan-500 mr-1.5" />').' Windows Phone';
            $deviceType = '📱 Celular';
        } elseif (preg_match('/Windows NT 10.0/i', $userAgent)) {
            $os = Blade::render('<x-bi-windows class="w-4 h-4 text-cyan-500 mr-1.5" />').' Windows 10/11';
        } elseif (preg_match('/Windows NT 6.3/i', $userAgent)) {
            $os = Blade::render('<x-gmdi-window-s class="w-4 h-4 text-white mr-1.5" />').' Windows 8.1';
        } elseif (preg_match('/Windows NT 6.1/i', $userAgent)) {
            $os = Blade::render('<x-icomoon-windows class="w-4 h-4 text-white mr-1.5" />').' Windows 7';
        } elseif (preg_match('/Macintosh|Mac OS X/i', $userAgent)) {
            $os = Blade::render('<x-bi-apple class="w-4 h-4 text-gray-200 mr-1.5" />').' Mac OS';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            $os = Blade::render('<img class="w-4 h-4  mr-1.5" src="'.asset('img/auditoria/linux.png').'" alt="" />').' Linux';
        }

        // Detecta navegador
        if (preg_match('/Chrome\/([0-9.]+)/i', $userAgent, $matches)) {
            $browser = Blade::render('<img class="w-4 h-4  mr-1.5" src="'.asset('img/auditoria/chrome.png').'" alt="" />').' Chrome v'.$matches[1];
        } elseif (preg_match('/Firefox\/([0-9.]+)/i', $userAgent, $matches)) {
            $browser = Blade::render('<img class="w-4 h-4  mr-1.5" src="'.asset('img/auditoria/firefox.png').'" alt="" />').'Firefox v'.$matches[1];
        } elseif (preg_match('/Safari\/([0-9.]+)/i', $userAgent) && ! preg_match('/Chrome/i', $userAgent)) {
            $browser = Blade::render('<img class="w-4 h-4  mr-1.5" src="'.asset('img/auditoria/safari.png').'" alt="" />').'Safari';
        }

        return [
            'sistema_operacional' => $os,
            'navegador' => $browser,
            'tipo_dispositivo' => $deviceType,
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
            'view',
        ];
    }
}
