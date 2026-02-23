-- Migration: Add hero_slide_limit to site_settings table
-- Description: Add column to configure maximum number of hero slides

ALTER TABLE `site_settings` 
ADD COLUMN `hero_slide_limit` INT NOT NULL DEFAULT 5 
AFTER `hero_slider_delay` 
COMMENT 'Maksimal jumlah slide hero yang ditampilkan';

-- Update existing records to set default value
UPDATE `site_settings` SET `hero_slide_limit` = 5 WHERE `hero_slide_limit` IS NULL;

-- Create index for better performance
ALTER TABLE `site_settings` ADD INDEX `idx_hero_slide_limit` (`hero_slide_limit`);