<?php

class empty_all_media_data extends CDbMigration {

    public function safeUp() {
        try {
            echo "\nEmpty Artist Category:\n";
            $this->dropForeignKey("fk_artist_artistcategory", '{{artistofcategory}}');
            $this->truncateTable('{{artistofcategory}}');
            $this->truncateTable('{{artistcategory}}');
            $this->addForeignKey("fk_artist_artistcategory", '{{artistofcategory}}', "category_id", '{{artistcategory}}', "id", "NO ACTION", "CASCADE");
        } catch (Exception $ex) {
            echo "\nEmpty Artist Category Error\n";
            echo $ex->getMessage();
            echo "\n";
        }

        try {
            echo "\nEmpty Music Charts:\n";
            $this->dropForeignKey("fk_music_musiccharts", '{{musicofcharts}}');
            $this->truncateTable('{{musicofcharts}}');
            $this->truncateTable('{{musiccharts}}');
            $this->addForeignKey("fk_music_musiccharts", '{{musicofcharts}}', "chart_id", '{{musiccharts}}', "id", "NO ACTION", "CASCADE");
        } catch (Exception $ex) {
            echo "\nEmpty Music Charts Error\n";
            echo $ex->getMessage();
            echo "\n";
        }

        try {
            echo "\nEmpty Media and Playlist:\n";
            $this->dropForeignKey("fk_device_playlist_media", '{{device_playlist}}');
            $this->dropForeignKey("fk_music_media", '{{musicofcharts}}');
            $this->truncateTable('{{device_playlist}}');
            $this->truncateTable('{{media}}');
            $this->addForeignKey("fk_device_playlist_media", '{{device_playlist}}', "media_id", '{{media}}', "id", "CASCADE", "CASCADE");
            $this->addForeignKey("fk_music_media", '{{musicofcharts}}', "media_id", '{{media}}', "id", "NO ACTION", "CASCADE");
        } catch (Exception $ex) {
            echo "\nEmpty Media and Playlist Error\n";
            echo $ex->getMessage();
            echo "\n";
        }

        try {
            echo "\nEmpty Artist and Category and Albumn:\n";
            $this->dropForeignKey("fk_artist_artist", '{{artistofcategory}}');
            $this->dropForeignKey("fk_media_artist", '{{media}}');
            $this->dropForeignKey("fk_media_category", '{{media}}');
            $this->dropForeignKey("fk_media_albumn", '{{media}}');
            $this->truncateTable('{{artist}}');
            $this->truncateTable('{{category}}');
            $this->truncateTable('{{albumn}}');
            $this->addForeignKey("fk_artist_artist", '{{artistofcategory}}', "artist_id", '{{artist}}', "id", "NO ACTION", "CASCADE");
            $this->addForeignKey("fk_media_artist", '{{media}}', "artist_id", '{{artist}}', "id", "NO ACTION", "CASCADE");
            $this->addForeignKey("fk_media_category", '{{media}}', "category_id", '{{category}}', "id", "NO ACTION", "CASCADE");
            $this->addForeignKey("fk_media_albumn", '{{media}}', "albumn_id", '{{albumn}}', "id", "NO ACTION", "CASCADE");
        } catch (Exception $ex) {
            echo "\nEmpty Artist and Category and Albumn Error\n";
            echo $ex->getMessage();
            echo "\n";
        }

        try {
            $this->truncateTable('{{temp_import}}');
        } catch (Exception $ex) {
            echo "\nEmpty Temp Import Error\n";
            echo $ex->getMessage();
            echo "\n";
        }

        try {
            // init albumn
            $this->execute('INSERT INTO ' . '{{albumn}}' . "(`id`,`name`,`description`) VALUES (1, 'KTV', 'KTV') ");
        } catch (Exception $ex) {
            
        }
        try {
            $this->execute('INSERT INTO ' . '{{category}}' . "(`id`,`name`,`description`) VALUES (1, 'POP', 'POP') ");
        } catch (Exception $ex) {
            
        }
    }

    public function safeDown() {
        //$this->dropColumn('{{musicofcharts}}', 'rank');
    }

}
