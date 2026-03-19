USE digital_second_brain;

INSERT INTO users (full_name, email, password_hash) VALUES
('Demo User', 'demo@example.com', '$2y$12$iq7VkmeuraEoY6rOtK142u8UA8UF2qxORcXmHl4xYaoJ36gozJcA.');

INSERT INTO categories (user_id, name, color) VALUES
(1, 'Learning', '#7c3aed'),
(1, 'Business Ideas', '#2563eb'),
(1, 'Personal', '#db2777'),
(1, 'Projects', '#0f766e'),
(1, 'Study', '#f59e0b'),
(1, 'Finance', '#475569');

INSERT INTO notes (user_id, category_id, title, content, priority, status, is_favorite, is_pinned, color_label) VALUES
(1, 1, 'Atomic Learning Stack', 'Build a repeatable learning system using spaced repetition, project-based practice, and weekly reviews.', 'High', 'Active', 1, 1, '#7c3aed'),
(1, 2, 'Micro-SaaS Ideas', 'List pain points in local retail businesses and validate simple B2B software opportunities.', 'Medium', 'Active', 1, 0, '#2563eb'),
(1, 4, 'Semester Launch Plan', 'Break the semester into milestones, deliverables, and a reflection system for continuous improvement.', 'High', 'Active', 0, 1, '#0f766e'),
(1, 6, 'Emergency Fund Notes', 'Track target fund size, monthly savings rule, and investment safety checklist.', 'Low', 'Archived', 0, 0, '#475569');

INSERT INTO tags (user_id, name) VALUES
(1, 'productivity'), (1, 'startup'), (1, 'systems'), (1, 'planning'), (1, 'money');

INSERT INTO note_tags (note_id, tag_id) VALUES
(1, 1), (1, 3), (2, 2), (3, 4), (4, 5);

INSERT INTO linked_notes (note_id, linked_note_id) VALUES
(1, 3), (2, 1), (3, 2);
