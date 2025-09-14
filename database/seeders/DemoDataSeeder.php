<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use App\Models\Card;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting demo data generation...');

        // Get existing user or create a demo user
        $existingUser = User::first();
        if (!$existingUser) {
            $existingUser = User::create([
                'name' => 'Demo User',
                'email' => 'demo@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
            $this->command->info('✅ Created demo user');
        } else {
            $this->command->info('✅ Using existing user: ' . $existingUser->email);
        }

        // Create additional demo users
        $this->createDemoUsers();
        
        // Create demo cards for all users
        $this->createDemoCards();
        
        // Create demo payments
        $this->createDemoPayments();
        
        // Create demo settings
        $this->createDemoSettings();

        $this->command->info('🎉 Demo data generation completed!');
        $this->command->info('📊 Summary:');
        $this->command->info('   - Users: ' . User::count());
        $this->command->info('   - Cards: ' . Card::count());
        $this->command->info('   - Payments: ' . Payment::count());
        $this->command->info('   - Settings: ' . Setting::count());
    }

    private function createDemoUsers()
    {
        $demoUsers = [
            [
                'email' => 'john.smith@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()->subDays(rand(1, 30)),
                'is_email_verified' => true,
            ],
            [
                'email' => 'sarah.johnson@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()->subDays(rand(1, 30)),
                'is_email_verified' => true,
            ],
            [
                'email' => 'michael.brown@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()->subDays(rand(1, 30)),
                'is_email_verified' => true,
            ],
            [
                'email' => 'emily.davis@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()->subDays(rand(1, 30)),
                'is_email_verified' => true,
            ],
            [
                'email' => 'david.wilson@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()->subDays(rand(1, 30)),
                'is_email_verified' => true,
            ],
            [
                'email' => 'lisa.anderson@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()->subDays(rand(1, 30)),
                'is_email_verified' => true,
            ],
            [
                'email' => 'robert.taylor@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()->subDays(rand(1, 30)),
                'is_email_verified' => true,
            ],
            [
                'email' => 'jennifer.martinez@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()->subDays(rand(1, 30)),
                'is_email_verified' => true,
            ],
            [
                'email' => 'christopher.lee@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()->subDays(rand(1, 30)),
                'is_email_verified' => true,
            ],
            [
                'email' => 'amanda.garcia@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()->subDays(rand(1, 30)),
                'is_email_verified' => true,
            ]
        ];

        foreach ($demoUsers as $userData) {
            if (!User::where('email', $userData['email'])->exists()) {
                User::create($userData);
            }
        }

        $this->command->info('✅ Created demo users');
    }

    private function createDemoCards()
    {
        $users = User::all();
        $cardTypes = ['Visa', 'Mastercard', 'American Express'];
        $countries = ['US', 'CA', 'GB', 'AU', 'DE', 'FR', 'IT', 'ES', 'NL', 'SE'];
        $cities = [
            'US' => ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix'],
            'CA' => ['Toronto', 'Vancouver', 'Montreal', 'Calgary', 'Ottawa'],
            'GB' => ['London', 'Manchester', 'Birmingham', 'Liverpool', 'Leeds'],
            'AU' => ['Sydney', 'Melbourne', 'Brisbane', 'Perth', 'Adelaide'],
            'DE' => ['Berlin', 'Munich', 'Hamburg', 'Cologne', 'Frankfurt'],
            'FR' => ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice'],
            'IT' => ['Rome', 'Milan', 'Naples', 'Turin', 'Palermo'],
            'ES' => ['Madrid', 'Barcelona', 'Valencia', 'Seville', 'Bilbao'],
            'NL' => ['Amsterdam', 'Rotterdam', 'The Hague', 'Utrecht', 'Eindhoven'],
            'SE' => ['Stockholm', 'Gothenburg', 'Malmö', 'Uppsala', 'Västerås']
        ];

        foreach ($users as $user) {
            // Each user gets 1-3 cards
            $cardCount = rand(1, 3);
            
            for ($i = 0; $i < $cardCount; $i++) {
                $country = $countries[array_rand($countries)];
                $city = $cities[$country][array_rand($cities[$country])];
                
                Card::create([
                    'name' => $this->generateNameFromEmail($user->email),
                    'surname' => $this->generateSurname(),
                    'card_number' => $this->generateCardNumber(),
                    'type_of_card' => $cardTypes[array_rand($cardTypes)],
                    'address' => $this->generateAddress(),
                    'city' => $city,
                    'state' => $this->generateState($country),
                    'country' => $country,
                    'postal_code' => $this->generatePostalCode($country),
                    'email_address' => $user->email,
                    'phone_number' => $this->generatePhoneNumber($country),
                    'user_id' => $user->id,
                ]);
            }
        }

        $this->command->info('✅ Created demo cards');
    }

    private function createDemoPayments()
    {
        $users = User::all();
        $serviceTypes = [
            'Online Shopping', 'Subscription Service', 'Utility Bill', 'Insurance Payment',
            'Restaurant', 'Gas Station', 'Grocery Store', 'Pharmacy', 'Entertainment',
            'Travel Booking', 'Hotel Reservation', 'Car Rental', 'Public Transport',
            'Mobile Phone Bill', 'Internet Service', 'Streaming Service', 'Gym Membership'
        ];
        
        $statuses = ['completed', 'pending', 'failed', 'refunded'];
        $currencies = ['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'ZMW'];

        foreach ($users as $user) {
            $userCards = $user->cards;
            if ($userCards->isEmpty()) continue;

            // Each user makes 5-25 payments over the last 90 days
            $paymentCount = rand(5, 25);
            
            for ($i = 0; $i < $paymentCount; $i++) {
                $card = $userCards->random();
                $createdAt = now()->subDays(rand(0, 90))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
                
                $amount = $this->generateRealisticAmount();
                $status = $this->getWeightedStatus();
                
                Payment::create([
                    'invoice_reference' => 'INV-' . strtoupper(uniqid()),
                    'description' => $this->generatePaymentDescription($serviceTypes[array_rand($serviceTypes)]),
                    'service_type' => $serviceTypes[array_rand($serviceTypes)],
                    'amount_spend' => $amount,
                    'payment' => 'card',
                    'transaction_reference' => 'TXN-' . strtoupper(uniqid()),
                    'reconciliaton_reference' => 'REC-' . strtoupper(uniqid()),
                    'status' => $status,
                    'user_id' => $user->id,
                    'card_id' => $card->id,
                    'currency' => $currencies[array_rand($currencies)],
                    'name' => $this->generateNameFromEmail($user->email),
                    'surname' => $card->surname,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }

        $this->command->info('✅ Created demo payments');
    }

    private function createDemoSettings()
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'ZACL Payment System',
                'type' => 'string',
                'description' => 'The name of the website'
            ],
            [
                'key' => 'site_description',
                'value' => 'Secure payment processing platform',
                'type' => 'string',
                'description' => 'Website description'
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Enable maintenance mode'
            ],
            [
                'key' => 'max_payment_amount',
                'value' => '10000',
                'type' => 'integer',
                'description' => 'Maximum payment amount allowed'
            ],
            [
                'key' => 'min_payment_amount',
                'value' => '1',
                'type' => 'integer',
                'description' => 'Minimum payment amount allowed'
            ],
            [
                'key' => 'supported_currencies',
                'value' => 'USD,EUR,GBP,CAD,AUD,ZMW',
                'type' => 'string',
                'description' => 'Comma-separated list of supported currencies'
            ],
            [
                'key' => 'payment_timeout',
                'value' => '30',
                'type' => 'integer',
                'description' => 'Payment timeout in minutes'
            ],
            [
                'key' => 'email_notifications',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable email notifications'
            ],
            [
                'key' => 'analytics_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable analytics tracking'
            ],
            [
                'key' => 'backup_frequency',
                'value' => 'daily',
                'type' => 'string',
                'description' => 'Database backup frequency'
            ]
        ];

        foreach ($settings as $setting) {
            if (!Setting::where('key', $setting['key'])->exists()) {
                Setting::create($setting);
            }
        }

        $this->command->info('✅ Created demo settings');
    }

    // Helper methods for generating realistic data
    private function generateNameFromEmail($email)
    {
        // Extract the part before @ and convert to a proper name
        $emailPart = explode('@', $email)[0];
        $nameParts = explode('.', $emailPart);
        
        if (count($nameParts) >= 2) {
            return ucfirst($nameParts[0]) . ' ' . ucfirst($nameParts[1]);
        } else {
            return ucfirst($emailPart);
        }
    }

    private function generateSurname()
    {
        $surnames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez'];
        return $surnames[array_rand($surnames)];
    }

    private function generateCardNumber()
    {
        // Generate realistic card numbers starting with 4 (Visa)
        $prefix = '4';
        $number = $prefix;
        for ($i = 0; $i < 15; $i++) {
            $number .= rand(0, 9);
        }
        return $number;
    }

    private function generateExpiryDate()
    {
        $month = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
        $year = date('y') + rand(1, 5);
        return $month . '/' . $year;
    }

    private function generateAddress()
    {
        $streets = ['Main St', 'Oak Ave', 'Pine Rd', 'Cedar Ln', 'Elm St', 'Maple Dr', 'First Ave', 'Second St'];
        $numbers = rand(100, 9999);
        return $numbers . ' ' . $streets[array_rand($streets)];
    }

    private function generateState($country)
    {
        $states = [
            'US' => ['NY', 'CA', 'TX', 'FL', 'IL', 'PA', 'OH', 'GA', 'NC', 'MI'],
            'CA' => ['ON', 'QC', 'BC', 'AB', 'MB', 'SK', 'NS', 'NB', 'NL', 'PE'],
            'GB' => ['England', 'Scotland', 'Wales', 'Northern Ireland'],
            'AU' => ['NSW', 'VIC', 'QLD', 'WA', 'SA', 'TAS', 'ACT', 'NT'],
            'DE' => ['BY', 'BW', 'NW', 'HE', 'SN', 'NI', 'ST', 'BE', 'MV', 'SH'],
            'FR' => ['IDF', 'ARA', 'HDF', 'NAQ', 'OCC', 'PDL', 'GES', 'NOR'],
            'IT' => ['LAZ', 'LOM', 'CAM', 'SIC', 'VEN', 'EMR', 'PUG', 'TOS'],
            'ES' => ['MAD', 'CAT', 'AND', 'VAL', 'GAL', 'CAS', 'MUR', 'EXT'],
            'NL' => ['NH', 'ZH', 'UT', 'GE', 'NB', 'LI', 'OV', 'DR', 'FL', 'ZE'],
            'SE' => ['ST', 'VG', 'SE', 'OG', 'VB', 'KR', 'HA', 'GA', 'SC', 'BD']
        ];
        
        return $states[$country][array_rand($states[$country])] ?? 'Unknown';
    }

    private function generatePostalCode($country)
    {
        $patterns = [
            'US' => function() { return rand(10000, 99999); },
            'CA' => function() { return chr(rand(65, 90)) . rand(0, 9) . chr(rand(65, 90)) . ' ' . rand(0, 9) . chr(rand(65, 90)) . rand(0, 9); },
            'GB' => function() { return chr(rand(65, 90)) . rand(1, 9) . chr(rand(65, 90)) . chr(rand(65, 90)) . ' ' . rand(1, 9) . chr(rand(65, 90)) . chr(rand(65, 90)); },
            'AU' => function() { return rand(1000, 9999); },
            'DE' => function() { return rand(10000, 99999); },
            'FR' => function() { return rand(10000, 99999); },
            'IT' => function() { return rand(10000, 99999); },
            'ES' => function() { return rand(10000, 99999); },
            'NL' => function() { return rand(1000, 9999) . chr(rand(65, 90)) . chr(rand(65, 90)); },
            'SE' => function() { return rand(10000, 99999); }
        ];
        
        return $patterns[$country] ? $patterns[$country]() : rand(10000, 99999);
    }

    private function generatePhoneNumber($country)
    {
        $patterns = [
            'US' => function() { return '+1-' . rand(200, 999) . '-' . rand(200, 999) . '-' . rand(1000, 9999); },
            'CA' => function() { return '+1-' . rand(200, 999) . '-' . rand(200, 999) . '-' . rand(1000, 9999); },
            'GB' => function() { return '+44-' . rand(20, 79) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999); },
            'AU' => function() { return '+61-' . rand(2, 9) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999); },
            'DE' => function() { return '+49-' . rand(30, 89) . '-' . rand(1000000, 9999999); },
            'FR' => function() { return '+33-' . rand(1, 9) . '-' . rand(10000000, 99999999); },
            'IT' => function() { return '+39-' . rand(3, 9) . '-' . rand(10000000, 99999999); },
            'ES' => function() { return '+34-' . rand(6, 9) . '-' . rand(10000000, 99999999); },
            'NL' => function() { return '+31-' . rand(6, 9) . '-' . rand(10000000, 99999999); },
            'SE' => function() { return '+46-' . rand(7, 9) . '-' . rand(10000000, 99999999); }
        ];
        
        return $patterns[$country] ? $patterns[$country]() : '+1-555-123-4567';
    }

    private function generateRealisticAmount()
    {
        // Generate realistic payment amounts with weighted distribution
        $rand = rand(1, 100);
        
        if ($rand <= 40) {
            // 40% chance: Small amounts ($1-$50)
            return rand(100, 5000) / 100;
        } elseif ($rand <= 70) {
            // 30% chance: Medium amounts ($50-$200)
            return rand(5000, 20000) / 100;
        } elseif ($rand <= 90) {
            // 20% chance: Large amounts ($200-$1000)
            return rand(20000, 100000) / 100;
        } else {
            // 10% chance: Very large amounts ($1000-$5000)
            return rand(100000, 500000) / 100;
        }
    }

    private function getWeightedStatus()
    {
        // Weighted distribution for realistic payment statuses
        $rand = rand(1, 100);
        
        if ($rand <= 85) {
            return 'completed'; // 85% success rate
        } elseif ($rand <= 95) {
            return 'pending'; // 10% pending
        } elseif ($rand <= 98) {
            return 'failed'; // 3% failed
        } else {
            return 'refunded'; // 2% refunded
        }
    }

    private function generatePaymentDescription($serviceType)
    {
        $descriptions = [
            'Online Shopping' => ['Amazon Purchase', 'eBay Transaction', 'Online Store Payment', 'Digital Download'],
            'Subscription Service' => ['Netflix Subscription', 'Spotify Premium', 'Adobe Creative Cloud', 'Microsoft Office 365'],
            'Utility Bill' => ['Electricity Bill', 'Water Bill', 'Gas Bill', 'Internet Bill'],
            'Insurance Payment' => ['Car Insurance', 'Health Insurance', 'Home Insurance', 'Life Insurance'],
            'Restaurant' => ['Dinner at Restaurant', 'Lunch Order', 'Coffee Shop', 'Fast Food'],
            'Gas Station' => ['Gas Station Fill-up', 'Fuel Purchase', 'Gas Station Snacks'],
            'Grocery Store' => ['Grocery Shopping', 'Supermarket Purchase', 'Food Shopping'],
            'Pharmacy' => ['Prescription Medication', 'Pharmacy Purchase', 'Health Products'],
            'Entertainment' => ['Movie Tickets', 'Concert Tickets', 'Theater Show', 'Sports Event'],
            'Travel Booking' => ['Flight Booking', 'Train Ticket', 'Bus Ticket', 'Taxi Ride'],
            'Hotel Reservation' => ['Hotel Booking', 'Accommodation Payment', 'Resort Stay'],
            'Car Rental' => ['Car Rental', 'Vehicle Hire', 'Rental Car Payment'],
            'Public Transport' => ['Metro Card', 'Bus Pass', 'Public Transport'],
            'Mobile Phone Bill' => ['Mobile Phone Bill', 'Cell Phone Payment', 'Phone Service'],
            'Internet Service' => ['Internet Bill', 'WiFi Service', 'Broadband Payment'],
            'Streaming Service' => ['Streaming Subscription', 'Video Service', 'Music Streaming'],
            'Gym Membership' => ['Gym Membership', 'Fitness Center', 'Health Club']
        ];
        
        $serviceDescriptions = $descriptions[$serviceType] ?? ['Payment', 'Transaction', 'Purchase'];
        return $serviceDescriptions[array_rand($serviceDescriptions)];
    }
}
