<?php

/**
 * Description of EmptyMediaCommand
 *
 * @author Joy
 */
class EmptyMediaCommand extends CConsoleCommand {

    const BASE_MIGRATION = 'm000000_000000_base';

    /**
     * @var string the directory that stores the migrations. This must be specified
     * in terms of a path alias, and the corresponding directory must exist.
     * Defaults to 'application.migrations' (meaning 'protected/migrations').
     */
    public $migrationPath = 'application.commands';

    /**
     * @var string the application component ID that specifies the database connection for
     * storing migration information. Defaults to 'db'.
     */
    public $connectionID = 'db';

    /**
     * @var boolean whether to execute the migration in an interactive mode. Defaults to true.
     * Set this to false when performing migration in a cron job or background process.
     */
    public $interactive = true;

    public function beforeAction($action, $params) {
        $path = Yii::getPathOfAlias($this->migrationPath);
        if ($path === false || !is_dir($path)) {
            echo 'Error: The migration directory does not exist: ' . $this->migrationPath . "\n";
            exit(1);
        }
        $this->migrationPath = $path;

        $yiiVersion = Yii::getVersion();
        echo "\nYii Migration Tool v1.0 (based on Yii v{$yiiVersion})\n\n";

        return parent::beforeAction($action, $params);
    }

    public function actionEmpty($args) {
        $message = "!!!\n";
        $message .= "Notice: This command will EMPTY ALL FOLLOWING DATA: Media, Artist, Category, Albumn, Music Charts and PlayList\n";
        $message .= "!!!\n";
        //$message .= "注意: 该命令将清空以下所有数据：歌曲，分类，专辑，歌手，播放列表，热歌榜，歌手分类！\n";
        $message .= "Would you like to continue?";

        if ($this->confirm($message, true)) {
            if ($this->migrateUp('empty_all_media_data') === false) {
                echo "\nEmpty failed. All later empty are canceled, you may do this command again.\n";
                return 2;
            }
            echo "\nMigrated up successfully.\n";
        }
    }

    public function confirm($message, $default = false) {
        if (!$this->interactive)
            return true;
        return parent::confirm($message, $default);
    }

    // true
    protected function migrateUp($class) {
        if ($class === self::BASE_MIGRATION)
            return;

        echo "*** applying $class\n";
        $start = microtime(true);
        $migration = $this->instantiateMigration($class);
        if ($migration->up() !== false) {
            $time = microtime(true) - $start;
            echo "*** applied $class (time: " . sprintf("%.3f", $time) . "s)\n\n";
        } else {
            $time = microtime(true) - $start;
            echo "*** failed to apply $class (time: " . sprintf("%.3f", $time) . "s)\n\n";
            return false;
        }
    }

    // true
    protected function instantiateMigration($class) {
        $file = $this->migrationPath . DIRECTORY_SEPARATOR . $class . '.php';
        require_once($file);
        $migration = new $class;
        $migration->setDbConnection($this->getDbConnection());
        return $migration;
    }

    /**
     * @var CDbConnection
     */
    private $_db;

    protected function getDbConnection() {
        if ($this->_db !== null)
            return $this->_db;
        elseif (($this->_db = Yii::app()->getComponent($this->connectionID)) instanceof CDbConnection)
            return $this->_db;

        echo "Error: CMigrationCommand.connectionID '{$this->connectionID}' is invalid. Please make sure it refers to the ID of a CDbConnection application component.\n";
        exit(1);
    }

}
