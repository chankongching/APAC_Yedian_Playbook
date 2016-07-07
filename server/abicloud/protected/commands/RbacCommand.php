<?php

class RbacCommand extends CConsoleCommand {

    private $_authManager;
    public $interactive = true;

    public function confirm($message, $default = false) {
        if (!$this->interactive)
            return true;
        return parent::confirm($message, $default);
    }
    
    public function getHelp() {

        $description = "DESCRIPTION\n";
        $description .= '    ' . "This command generates an initial RBAC authorization hierarchy.\n";
        return parent::getHelp() . $description;
    }

    /**
     * The default action - create the RBAC structure.
     */
    public function actionIndex() {

        $this->ensureAuthManagerDefined();
        
        echo "Operation is not needed.\n";
        return;

        //provide the oportunity for the use to abort the request
        $message = "This command will create three roles: Super, Member, and Guest\n";
        $message .= " and the following permissions,\n";
        $message .= " and will delete all exists users:\n";
        $message .= "create, read, update and delete user\n";
        $message .= "create, read, update and delete category\n";
        $message .= "upload, download, update and delete files\n";
        $message .= "Would you like to continue?";

        //check the input from the user and continue if 
        //they indicated yes to the above question
        if ($this->confirm($message, true)) {
            //first we need to remove all operations, 
            //roles, child relationship and assignments
            // TODO clear auth item first
            //$this->_authManager->clearAll();

            //create the lowest level operations for users
            $this->_authManager->createOperation(
                    "createUser", "create a new user");
            $this->_authManager->createOperation(
                    "readUser", "read user profile information");
            $this->_authManager->createOperation(
                    "updateUser", "update a users in-formation");
            $this->_authManager->createOperation(
                    "deleteUser", "remove a user ");

            //create the lowest level operations for category
            $this->_authManager->createOperation(
                    "createCategory", "create a new category");
            $this->_authManager->createOperation(
                    "readCategory", "read category information");
            $this->_authManager->createOperation(
                    "updateCategory", "update category information");
            $this->_authManager->createOperation(
                    "deleteCategory", "delete a category");

            //create the lowest level operations for files
            $this->_authManager->createOperation(
                    "uploadFile", "upload a new file");
            $this->_authManager->createOperation(
                    "downloadFile", "download a file");
            $this->_authManager->createOperation(
                    "updateFile", "update file information");
            $this->_authManager->createOperation(
                    "deleteFile", "delete an file from a category");

            //create the reader role and add the appropriate 
            //permissions as children to this role
            $role = $this->_authManager->createRole("Member");
            $role->addChild("readCategory");
            $role->addChild("downloadFile");

            //create the member role, and add the appropriate 
            //permissions, as well as the reader role itself, as children
            $role = $this->_authManager->createRole("Admin");
            $role->addChild("reader");
            $role->addChild("readUser");
            $role->addChild("uploadFile");
            $role->addChild("updateFile");
            $role->addChild("deleteFile");

            //create the owner role, and add the appropriate permissions, 
            //as well as both the reader and member roles as children
            $role = $this->_authManager->createRole("Super");
            $role->addChild("reader");
            $role->addChild("member");
            $role->addChild("createUser");
            $role->addChild("updateUser");
            $role->addChild("deleteUser");
            $role->addChild("createCategory");
            $role->addChild("updateCategory");
            $role->addChild("deleteCategory");

            //provide a message indicating success
            echo "Authorization hierarchy successfully generated.\n";
        } else
            echo "Operation cancelled.\n";
    }

    public function actionDelete() {
        $this->ensureAuthManagerDefined();
        $message = "This command will clear all RBAC definitions.\n";
        $message .= "Would you like to continue?";
        //check the input from the user and continue if they indicated 
        //yes to the above question
        if ($this->confirm($message)) {
            $this->_authManager->clearAll();
            echo "Authorization hierarchy removed.\n";
        } else
            echo "Delete operation cancelled.\n";
    }

    protected function ensureAuthManagerDefined() {
        //ensure that an authManager is defined as this is mandatory for creating an auth heirarchy
        if (($this->_authManager = Yii::app()->authManager) === null) {
            $message = "Error: an authorization manager, named 'authManager' must be con-figured to use this command.";
            $this->usageError($message);
        }
    }

}
