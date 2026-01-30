<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Follow;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        // Mixed names: Arabic, English, French
        $names = [
            // Arabic names
            'Ahmed Al-Mansouri', 'Fatima Zahra', 'Mohammed Ben Ali', 'Aisha Al-Rashid', 'Omar Hassan',
            'Layla Al-Fahad', 'Youssef Al-Mahmoud', 'Nour Al-Din', 'Sara Al-Khalil', 'Khalid Al-Saud',
            // English names
            'John Smith', 'Emily Johnson', 'Michael Brown', 'Sarah Williams', 'David Jones',
            'Jessica Davis', 'Christopher Miller', 'Amanda Wilson', 'Matthew Taylor', 'Jennifer Anderson',
            // French names
            'Jean Dupont', 'Marie Martin', 'Pierre Bernard', 'Sophie Dubois', 'Luc Moreau',
            'Camille Laurent', 'Antoine Rousseau', 'Claire Girard', 'Thomas Lefebvre', 'Julie Petit',
        ];
        
        // English posts and comments
        $posts = [
            'Just finished an amazing workout! Feeling energized and ready for the day. 💪',
            'Beautiful sunset today. Sometimes you just need to stop and appreciate the little things in life.',
            'Working on a new project that I\'m really excited about. Can\'t wait to share it with everyone!',
            'Coffee and coding - the perfect combination for a productive morning.',
            'Had a great time at the concert last night. The energy was incredible!',
            'Reading a fascinating book about technology and innovation. Highly recommend it!',
            'Just tried a new recipe and it turned out amazing. Cooking is such a relaxing hobby.',
            'Weekend vibes: good music, good food, and great company. Life is good!',
            'Started learning a new language today. It\'s challenging but so rewarding.',
            'Nature walk in the park was exactly what I needed. Fresh air does wonders for the mind.',
            'Working on improving my photography skills. Practice makes perfect!',
            'Grateful for all the amazing people in my life. You know who you are! ❤️',
            'New music discovery of the day - this artist is incredible!',
            'Just finished watching an amazing documentary. Always love learning something new.',
            'Planning a trip for next month. So excited to explore new places!',
        ];
        
        $comments = [
            'That sounds amazing! Keep up the great work!',
            'I totally agree with you on this one.',
            'Thanks for sharing this! Really helpful.',
            'This is so inspiring! Thank you.',
            'Love this! Can\'t wait to see more.',
            'You\'re absolutely right about that.',
            'This made my day! Thank you.',
            'Great post! Looking forward to more updates.',
            'I had a similar experience recently.',
            'This is exactly what I needed to hear today.',
            'Amazing work! Keep it up!',
            'So happy for you! Congratulations!',
            'This is beautiful! Thanks for sharing.',
            'I completely understand how you feel.',
            'You\'re doing great! Don\'t give up!',
        ];
        
        // Create 30 users with mixed names
        $users = collect();
        foreach ($names as $name) {
            $users->push(User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@linkup.com',
                'password' => Hash::make('password'),
                'bio' => $faker->optional(0.7)->randomElement([
                    'Software developer passionate about technology',
                    'Photography enthusiast and travel lover',
                    'Fitness enthusiast and health advocate',
                    'Music lover and aspiring musician',
                    'Bookworm and coffee addict',
                    'Nature enthusiast and outdoor adventurer',
                    'Foodie and cooking enthusiast',
                    'Art lover and creative soul',
                    'Student and lifelong learner',
                    'Entrepreneur and dreamer',
                ]),
                'email_verified_at' => now(),
            ]));
        }

        // Create 100 posts with English content
        $postCollection = collect();
        for ($i = 0; $i < 100; $i++) {
            $postCollection->push(Post::create([
                'user_id' => $users->random()->id,
                'content' => $faker->randomElement($posts),
            ]));
        }

        // Create 300 comments with English content
        for ($i = 0; $i < 300; $i++) {
            Comment::create([
                'post_id' => $postCollection->random()->id,
                'user_id' => $users->random()->id,
                'content' => $faker->randomElement($comments),
            ]);
        }

        // Create likes (avoid duplicates)
        foreach ($postCollection as $post) {
            $likers = $users->random(rand(0, min(15, $users->count())));
            foreach ($likers as $liker) {
                Like::firstOrCreate([
                    'post_id' => $post->id,
                    'user_id' => $liker->id,
                ]);
            }
        }

        // Create follow relationships
        foreach ($users as $user) {
            $toFollow = $users->where('id', '!=', $user->id)->random(rand(3, 10));
            foreach ($toFollow as $followed) {
                Follow::firstOrCreate([
                    'follower_id' => $user->id,
                    'following_id' => $followed->id,
                ]);
            }
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('- ' . $users->count() . ' users (Arabic, English, and French names)');
        $this->command->info('- ' . $postCollection->count() . ' posts (English content)');
        $this->command->info('- 300 comments (English content)');
        $this->command->info('- Follow relationships');
        $this->command->info('');
        $this->command->info('Test with any user email (password: password)');
    }
}
