<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Notifications\TestEmailNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;

class SystemSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'System Settings';
    protected static ?string $navigationGroup = 'Configuration';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.system-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'stripe_key' => Setting::get('stripe_key', config('services.stripe.key')),
            'stripe_secret' => Setting::get('stripe_secret', config('services.stripe.secret')),
            'stripe_webhook_secret' => Setting::get('stripe_webhook_secret', config('services.stripe.webhook.secret')),
            'admin_notification_email' => Setting::get('admin_notification_email', env('ADMIN_NOTIFICATION_EMAIL')),
            'mail_from_name' => Setting::get('mail_from_name', config('mail.from.name')),
            'mail_from_address' => Setting::get('mail_from_address', config('mail.from.address')),
            // About Page - defaults
            'about_hero_subtitle' => Setting::get('about_hero_subtitle', "Découvrez l'histoire et les valeurs qui nous animent."),
            'about_history_title' => Setting::get('about_history_title', 'Notre Histoire'),
            'about_history_body' => Setting::get('about_history_body', "L'Association Westmount a été fondée en 2024 par un groupe de familles montréalaises qui souhaite promouvoir un système de solidarité pour optimiser les possibilités d'entraide lors du départ d’un proche parent."),
            'about_history_image' => Setting::get('about_history_image'),
            'about_mission_text' => Setting::get('about_mission_text', "Offrir un soutien financier et moral à nos membres lors des décès d'un proche, en créant un réseau d'entraide basé sur la solidarité et la confiance mutuelle."),
            'about_vision_text' => Setting::get('about_vision_text', "Être une référence en matière d'associations d'entraide au Canada, en développant un modèle durable et transparent."),
            'about_values_text' => Setting::get('about_values_text', 'Solidarité, Transparence, Respect, Intégrité et Compassion guident nos actions.'),
            // Stats
            'about_stats_members_value' => Setting::get('about_stats_members_value', '1000+'),
            'about_stats_members_label' => Setting::get('about_stats_members_label', 'Membres actifs'),
            'about_stats_contrib_value' => Setting::get('about_stats_contrib_value', '$2M+'),
            'about_stats_contrib_label' => Setting::get('about_stats_contrib_label', 'Contributions versées'),
            'about_stats_years_value' => Setting::get('about_stats_years_value', '1'),
            'about_stats_years_label' => Setting::get('about_stats_years_label', "Années d'expérience"),
            'about_stats_satisfaction_value' => Setting::get('about_stats_satisfaction_value', '100%'),
            'about_stats_satisfaction_label' => Setting::get('about_stats_satisfaction_label', 'Satisfaction'),

            // Footer defaults
            'footer_about_text' => Setting::get('footer_about_text', "Une communauté d'entraide et de solidarité qui accompagne ses membres dans les moments difficiles."),
            'footer_phone' => Setting::get('footer_phone', '514-566-4029'),
            'footer_email' => Setting::get('footer_email', 'info@associationwestmount.com'),
            'footer_address' => Setting::get('footer_address', "Montréal, QC, Canada"),
            'footer_hours' => Setting::get('footer_hours', 'Lun-Ven: 9h-17h'),
            'footer_facebook' => Setting::get('footer_facebook'),
            'footer_twitter' => Setting::get('footer_twitter'),
            'footer_linkedin' => Setting::get('footer_linkedin'),
            'footer_instagram' => Setting::get('footer_instagram'),

            // Death Contributions page defaults (steps)
            'dc_step1_title' => Setting::get('dc_step1_title', 'Contribution en cas de décès'),
            'dc_step1_body' => Setting::get('dc_step1_body', 'Chaque membre contribue selon sa catégorie'),
            'dc_step2_title' => Setting::get('dc_step2_title', 'Fonds de solidarité'),
            'dc_step2_body' => Setting::get('dc_step2_body', 'Les contributions sont versées dans un fonds de solidarité'),
            'dc_step3_title' => Setting::get('dc_step3_title', 'Aide financière'),
            'dc_step3_body' => Setting::get('dc_step3_body', 'En cas de décès, la famille reçoit une aide financière'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make('Stripe')
                    ->schema([
                        Forms\Components\TextInput::make('stripe_key')
                            ->label('Publishable Key (pk_...)')
                            ->required(),
                        Forms\Components\TextInput::make('stripe_secret')
                            ->label('Secret Key (sk_...)')
                            ->password()
                            ->revealable()
                            ->required(),
                        Forms\Components\TextInput::make('stripe_webhook_secret')
                            ->label('Webhook Signing Secret (whsec_...)')
                            ->password()
                            ->revealable(),
                    ])->columns(2),

                Forms\Components\Section::make('Notifications')
                    ->schema([
                        Forms\Components\TextInput::make('admin_notification_email')
                            ->email()
                            ->label('Admin Notification Email')
                            ->helperText("Emails d'admin: nouvelles inscriptions et paiements reçus")
                            ->required(),
                        Forms\Components\TextInput::make('test_email_target')
                            ->email()
                            ->label('Test Email Recipient')
                            ->helperText('Adresse pour envoyer un email de test'),
                    ])->columns(2),

                Forms\Components\Section::make('Mail From')
                    ->schema([
                        Forms\Components\TextInput::make('mail_from_name')
                            ->label('From Name'),
                        Forms\Components\TextInput::make('mail_from_address')
                            ->label('From Address')
                            ->email(),
                    ])->columns(2),

                Forms\Components\Section::make('About Page')
                    ->schema([
                        Forms\Components\TextInput::make('about_hero_subtitle')
                            ->label('Hero Subtitle')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('about_history_title')
                            ->label('History Title')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('about_history_body')
                            ->label('History Body')
                            ->rows(5),
                        Forms\Components\FileUpload::make('about_history_image')
                            ->label('History Image')
                            ->image()
                            ->directory('about')
                            ->imageEditor()
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeMode('cover')
                            ->imagePreviewHeight('200')
                            ->downloadable()
                            ->openable(),
                        Forms\Components\Textarea::make('about_mission_text')
                            ->label('Mission Text')
                            ->rows(3),
                        Forms\Components\Textarea::make('about_vision_text')
                            ->label('Vision Text')
                            ->rows(3),
                        Forms\Components\Textarea::make('about_values_text')
                            ->label('Values Text')
                            ->rows(3),

                        Forms\Components\Fieldset::make('Statistics')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('about_stats_members_value')->label('Members Value'),
                                        Forms\Components\TextInput::make('about_stats_members_label')->label('Members Label'),
                                    ]),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('about_stats_contrib_value')->label('Contributions Value'),
                                        Forms\Components\TextInput::make('about_stats_contrib_label')->label('Contributions Label'),
                                    ]),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('about_stats_years_value')->label('Years Value'),
                                        Forms\Components\TextInput::make('about_stats_years_label')->label('Years Label'),
                                    ]),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('about_stats_satisfaction_value')->label('Satisfaction Value'),
                                        Forms\Components\TextInput::make('about_stats_satisfaction_label')->label('Satisfaction Label'),
                                    ]),
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Footer')
                    ->schema([
                        Forms\Components\Textarea::make('footer_about_text')
                            ->label('About Text')
                            ->rows(3),
                        Forms\Components\TextInput::make('footer_phone')
                            ->label('Phone'),
                        Forms\Components\TextInput::make('footer_email')
                            ->label('Email'),
                        Forms\Components\Textarea::make('footer_address')
                            ->label('Address')
                            ->rows(3),
                        Forms\Components\TextInput::make('footer_hours')
                            ->label('Hours (e.g., Lun-Ven: 9h-17h)'),
                        Forms\Components\Fieldset::make('Social Links (optional)')
                            ->schema([
                                Forms\Components\TextInput::make('footer_facebook')->label('Facebook URL'),
                                Forms\Components\TextInput::make('footer_twitter')->label('Twitter URL'),
                                Forms\Components\TextInput::make('footer_linkedin')->label('LinkedIn URL'),
                                Forms\Components\TextInput::make('footer_instagram')->label('Instagram URL'),
                            ])->columns(2),
                    ])->columns(2),

                Forms\Components\Section::make('Death Contributions Page')
                    ->schema([
                        Forms\Components\Fieldset::make('Étape 1')
                            ->schema([
                                Forms\Components\TextInput::make('dc_step1_title')->label('Titre Étape 1')->maxLength(255),
                                Forms\Components\Textarea::make('dc_step1_body')->label('Texte Étape 1')->rows(3),
                            ]),
                        Forms\Components\Fieldset::make('Étape 2')
                            ->schema([
                                Forms\Components\TextInput::make('dc_step2_title')->label('Titre Étape 2')->maxLength(255),
                                Forms\Components\Textarea::make('dc_step2_body')->label('Texte Étape 2')->rows(3),
                            ]),
                        Forms\Components\Fieldset::make('Étape 3')
                            ->schema([
                                Forms\Components\TextInput::make('dc_step3_title')->label('Titre Étape 3')->maxLength(255),
                                Forms\Components\Textarea::make('dc_step3_body')->label('Texte Étape 3')->rows(3),
                            ]),
                    ])->columns(2),
            ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        // Persist to settings table
        Setting::set('stripe_key', $state['stripe_key'] ?? '');
        Setting::set('stripe_secret', $state['stripe_secret'] ?? '');
        Setting::set('stripe_webhook_secret', $state['stripe_webhook_secret'] ?? '');
        Setting::set('admin_notification_email', $state['admin_notification_email'] ?? '');
        Setting::set('mail_from_name', $state['mail_from_name'] ?? '');
        Setting::set('mail_from_address', $state['mail_from_address'] ?? '');

        // About Page settings
        Setting::set('about_hero_subtitle', $state['about_hero_subtitle'] ?? '');
        Setting::set('about_history_title', $state['about_history_title'] ?? '');
        Setting::set('about_history_body', $state['about_history_body'] ?? '');
        Setting::set('about_history_image', $state['about_history_image'] ?? '');
        Setting::set('about_mission_text', $state['about_mission_text'] ?? '');
        Setting::set('about_vision_text', $state['about_vision_text'] ?? '');
        Setting::set('about_values_text', $state['about_values_text'] ?? '');
        Setting::set('about_stats_members_value', $state['about_stats_members_value'] ?? '');
        Setting::set('about_stats_members_label', $state['about_stats_members_label'] ?? '');
        Setting::set('about_stats_contrib_value', $state['about_stats_contrib_value'] ?? '');
        Setting::set('about_stats_contrib_label', $state['about_stats_contrib_label'] ?? '');
        Setting::set('about_stats_years_value', $state['about_stats_years_value'] ?? '');
        Setting::set('about_stats_years_label', $state['about_stats_years_label'] ?? '');
        Setting::set('about_stats_satisfaction_value', $state['about_stats_satisfaction_value'] ?? '');
        Setting::set('about_stats_satisfaction_label', $state['about_stats_satisfaction_label'] ?? '');

        // Footer settings
        Setting::set('footer_about_text', $state['footer_about_text'] ?? '');
        Setting::set('footer_phone', $state['footer_phone'] ?? '');
        Setting::set('footer_email', $state['footer_email'] ?? '');
        Setting::set('footer_address', $state['footer_address'] ?? '');
        Setting::set('footer_hours', $state['footer_hours'] ?? '');
        Setting::set('footer_facebook', $state['footer_facebook'] ?? '');
        Setting::set('footer_twitter', $state['footer_twitter'] ?? '');
        Setting::set('footer_linkedin', $state['footer_linkedin'] ?? '');
        Setting::set('footer_instagram', $state['footer_instagram'] ?? '');

        // Death Contributions page steps
        Setting::set('dc_step1_title', $state['dc_step1_title'] ?? '');
        Setting::set('dc_step1_body', $state['dc_step1_body'] ?? '');
        Setting::set('dc_step2_title', $state['dc_step2_title'] ?? '');
        Setting::set('dc_step2_body', $state['dc_step2_body'] ?? '');
        Setting::set('dc_step3_title', $state['dc_step3_title'] ?? '');
        Setting::set('dc_step3_body', $state['dc_step3_body'] ?? '');

        // Also update .env for mail + admin email for reliability
        $this->writeEnv([
            'MAIL_FROM_NAME' => $state['mail_from_name'] ?? '',
            'MAIL_FROM_ADDRESS' => $state['mail_from_address'] ?? '',
            'ADMIN_NOTIFICATION_EMAIL' => $state['admin_notification_email'] ?? '',
        ]);

        // Reload config and restart queue workers
        Artisan::call('config:clear');
        Artisan::call('queue:restart');

        FilamentNotification::make()
            ->title('Paramètres enregistrés')
            ->success()
            ->send();
    }

    public function sendTestEmail(): void
    {
        $state = $this->form->getState();
        $to = $state['test_email_target'] ?? ($state['admin_notification_email'] ?? null);
        if (!$to) {
            FilamentNotification::make()->title('Veuillez fournir une adresse email de test')->danger()->send();
            return;
        }

        try {
            Notification::route('mail', $to)->notify(new TestEmailNotification());
            FilamentNotification::make()->title('Email de test envoyé à '.$to)->success()->send();
        } catch (\Throwable $e) {
            FilamentNotification::make()->title('Échec de l\'envoi: '.$e->getMessage())->danger()->send();
        }
    }

    protected function writeEnv(array $pairs): void
    {
        $path = base_path('.env');
        if (!is_writable($path)) {
            return;
        }

        $contents = file_get_contents($path);
        foreach ($pairs as $key => $value) {
            $pattern = "/^{$key}=.*$/m";
            $line = $key.'='.(str_contains($value ?? '', ' ') ? '"'.addslashes($value).'"' : $value);
            if (preg_match($pattern, $contents)) {
                $contents = preg_replace($pattern, $line, $contents);
            } else {
                $contents .= PHP_EOL.$line;
            }
        }
        file_put_contents($path, $contents);
    }
}
