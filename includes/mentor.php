<?php
/**
 * CodeReview.pl - Mentor Model
 * Handles mentor-related data and operations
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/logger.php';

class Mentor {
    /**
     * Get all mentors (from DB or fallback to featured)
     */
    public static function getAll(int $limit = 10): array {
        $db = get_db();
        if ($db) {
            try {
                $stmt = $db->prepare("SELECT * FROM users WHERE role = 'mentor' LIMIT :limit");
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->execute();
                $mentors = $stmt->fetchAll();
                
                if (!empty($mentors)) {
                    return $mentors;
                }
            } catch (PDOException $e) {
                Logger::error('Failed to fetch mentors from DB', ['error' => $e->getMessage()]);
            }
        }
        
        // Fallback/Featured mentors if DB empty or unavailable
        return self::getFeaturedMentors();
    }

    /**
     * Featured mentors for landing page/fallback
     */
    public static function getFeaturedMentors(): array {
        return [
            [
                'id' => 1,
                'name' => 'Tom Sapletta',
                'github_id' => 'sapletta',
                'avatar_url' => 'https://github.com/sapletta.png',
                'bio' => 'Fullstack Dev, Docker Expert, PHP enthusiast.',
                'specialties' => ['PHP', 'Docker', 'Architecture'],
                'rating' => 4.9,
                'price_pln' => 150
            ],
            [
                'id' => 2,
                'name' => 'Anna Kowalska',
                'github_id' => 'annak',
                'avatar_url' => 'https://i.pravatar.cc/150?u=anna',
                'bio' => 'Frontend specialist, React & Tailwind expert.',
                'specialties' => ['React', 'CSS', 'UI/UX'],
                'rating' => 5.0,
                'price_pln' => 120
            ],
            [
                'id' => 3,
                'name' => 'Piotr Nowak',
                'github_id' => 'pnowak',
                'avatar_url' => 'https://i.pravatar.cc/150?u=piotr',
                'bio' => 'Backend dev, Go & Python lover.',
                'specialties' => ['Go', 'Python', 'K8s'],
                'rating' => 4.8,
                'price_pln' => 140
            ]
        ];
    }
}
