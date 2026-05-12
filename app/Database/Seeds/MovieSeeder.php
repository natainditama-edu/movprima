<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        // ----------------------------------------------------------------
        // Movies data
        // ----------------------------------------------------------------
        $movies = [
            [
                'title'        => 'Inception',
                'slug'         => 'inception',
                'synopsis'     => 'A skilled thief who steals corporate secrets through dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.',
                'director'     => 'Christopher Nolan',
                'release_year' => 2010,
                'duration'     => 148,
                'language'     => 'English',
                'country'      => 'USA',
                'status'       => 'published',
                'genres'       => ['sci-fi', 'thriller'],
            ],
            [
                'title'        => 'The Dark Knight',
                'slug'         => 'the-dark-knight',
                'synopsis'     => 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests.',
                'director'     => 'Christopher Nolan',
                'release_year' => 2008,
                'duration'     => 152,
                'language'     => 'English',
                'country'      => 'USA',
                'status'       => 'published',
                'genres'       => ['action', 'thriller'],
            ],
            [
                'title'        => 'Interstellar',
                'slug'         => 'interstellar',
                'synopsis'     => 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity\'s survival.',
                'director'     => 'Christopher Nolan',
                'release_year' => 2014,
                'duration'     => 169,
                'language'     => 'English',
                'country'      => 'USA',
                'status'       => 'published',
                'genres'       => ['sci-fi', 'drama'],
            ],
            [
                'title'        => 'Parasite',
                'slug'         => 'parasite',
                'synopsis'     => 'Greed and class discrimination threaten the newly formed symbiotic relationship between the wealthy Park family and the destitute Kim clan.',
                'director'     => 'Bong Joon-ho',
                'release_year' => 2019,
                'duration'     => 132,
                'language'     => 'Korean',
                'country'      => 'South Korea',
                'status'       => 'published',
                'genres'       => ['drama', 'thriller'],
            ],
            [
                'title'        => 'Spirited Away',
                'slug'         => 'spirited-away',
                'synopsis'     => 'During her family\'s move to the suburbs, a sullen 10-year-old girl wanders into a world ruled by gods, witches, and spirits.',
                'director'     => 'Hayao Miyazaki',
                'release_year' => 2001,
                'duration'     => 125,
                'language'     => 'Japanese',
                'country'      => 'Japan',
                'status'       => 'published',
                'genres'       => ['animation', 'fantasy'],
            ],
            [
                'title'        => 'The Shawshank Redemption',
                'slug'         => 'the-shawshank-redemption',
                'synopsis'     => 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.',
                'director'     => 'Frank Darabont',
                'release_year' => 1994,
                'duration'     => 142,
                'language'     => 'English',
                'country'      => 'USA',
                'status'       => 'published',
                'genres'       => ['drama'],
            ],
            [
                'title'        => 'Get Out',
                'slug'         => 'get-out',
                'synopsis'     => 'A young African-American visits his white girlfriend\'s parents for the weekend, where his simmering unease about their rural compound grows to a disturbing peak.',
                'director'     => 'Jordan Peele',
                'release_year' => 2017,
                'duration'     => 104,
                'language'     => 'English',
                'country'      => 'USA',
                'status'       => 'published',
                'genres'       => ['horror', 'thriller'],
            ],
            [
                'title'        => 'Coco',
                'slug'         => 'coco',
                'synopsis'     => 'Aspiring musician Miguel, confronted with his family\'s ban on music, enters the Land of the Dead to find his great-great-grandfather.',
                'director'     => 'Lee Unkrich',
                'release_year' => 2017,
                'duration'     => 105,
                'language'     => 'English',
                'country'      => 'USA',
                'status'       => 'published',
                'genres'       => ['animation', 'fantasy'],
            ],
            [
                'title'        => 'La La Land',
                'slug'         => 'la-la-land',
                'synopsis'     => 'While navigating their careers in Los Angeles, a pianist and an actress fall in love while attempting to reconcile their aspirations for the future.',
                'director'     => 'Damien Chazelle',
                'release_year' => 2016,
                'duration'     => 128,
                'language'     => 'English',
                'country'      => 'USA',
                'status'       => 'published',
                'genres'       => ['romance', 'drama'],
            ],
            [
                'title'        => 'Avengers: Endgame',
                'slug'         => 'avengers-endgame',
                'synopsis'     => 'After the devastating events of Infinity War, the Avengers assemble once more to reverse Thanos\'s actions and restore balance to the universe.',
                'director'     => 'Anthony Russo',
                'release_year' => 2019,
                'duration'     => 181,
                'language'     => 'English',
                'country'      => 'USA',
                'status'       => 'published',
                'genres'       => ['action', 'sci-fi'],
            ],
            [
                'title'        => 'Joker',
                'slug'         => 'joker',
                'synopsis'     => 'In Gotham City, mentally troubled comedian Arthur Fleck embarks on a downward spiral of revolution and bloody crime.',
                'director'     => 'Todd Phillips',
                'release_year' => 2019,
                'duration'     => 122,
                'language'     => 'English',
                'country'      => 'USA',
                'status'       => 'published',
                'genres'       => ['drama', 'thriller'],
            ],
            [
                'title'        => 'Your Name',
                'slug'         => 'your-name',
                'synopsis'     => 'Two strangers find themselves linked in a bizarre way. When a connection forms, will distance be an obstacle or will fate bring them together?',
                'director'     => 'Makoto Shinkai',
                'release_year' => 2016,
                'duration'     => 106,
                'language'     => 'Japanese',
                'country'      => 'Japan',
                'status'       => 'published',
                'genres'       => ['animation', 'romance'],
            ],
            [
                'title'        => 'Oppenheimer',
                'slug'         => 'oppenheimer',
                'synopsis'     => 'The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb during World War II.',
                'director'     => 'Christopher Nolan',
                'release_year' => 2023,
                'duration'     => 180,
                'language'     => 'English',
                'country'      => 'USA',
                'status'       => 'published',
                'genres'       => ['drama', 'thriller'],
            ],
            [
                'title'        => 'Everything Everywhere All at Once',
                'slug'         => 'everything-everywhere-all-at-once',
                'synopsis'     => 'An aging Chinese immigrant is swept up in an insane adventure, where she alone can save the world by exploring other universes connecting with the lives she could have led.',
                'director'     => 'Daniel Kwan',
                'release_year' => 2022,
                'duration'     => 139,
                'language'     => 'English',
                'country'      => 'USA',
                'status'       => 'published',
                'genres'       => ['action', 'sci-fi', 'comedy'],
            ],
            [
                'title'        => 'The Grand Budapest Hotel',
                'slug'         => 'the-grand-budapest-hotel',
                'synopsis'     => 'The adventures of Gustave H, a legendary concierge at a famous European hotel between the wars, and Zero Moustafa, the lobby boy who becomes his most trusted friend.',
                'director'     => 'Wes Anderson',
                'release_year' => 2014,
                'duration'     => 99,
                'language'     => 'English',
                'country'      => 'USA',
                'status'       => 'published',
                'genres'       => ['comedy', 'drama'],
            ],
        ];

        // ----------------------------------------------------------------
        // Fetch genre slug → id map
        // ----------------------------------------------------------------
        $genreRows  = $this->db->table('genres')->select('id, slug')->get()->getResultArray();
        $genreMap   = array_column($genreRows, 'id', 'slug'); // ['action' => 1, ...]

        // ----------------------------------------------------------------
        // Insert movies + pivot rows
        // ----------------------------------------------------------------
        $insertedCount = 0;

        foreach ($movies as $movie) {
            $genreSlugs = $movie['genres'];
            unset($movie['genres']);

            $movie['avg_rating']   = 0.00;
            $movie['review_count'] = 0;
            $movie['created_at']   = $now;
            $movie['updated_at']   = $now;

            $this->db->table('movies')->insert($movie);
            $movieId = $this->db->insertID();

            // Insert pivot rows
            foreach ($genreSlugs as $slug) {
                if (isset($genreMap[$slug])) {
                    $this->db->table('movie_genres')->insert([
                        'movie_id' => $movieId,
                        'genre_id' => $genreMap[$slug],
                    ]);
                }
            }

            $insertedCount++;
        }

        echo "  Seeded: {$insertedCount} movies with genre relations\n";
    }
}
