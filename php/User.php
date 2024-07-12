<?php
include_once 'db.php';
include_once  'db-queries.php';
/**
 * User object class
 * @author Sean Calkins
 */
class User
{
    public $username;
    public $password;
    public $favTeams;
    public$userId;

    /**
     * Constructor to create a new user object.
     *
     * This constructor initializes a new user object with the provided username and password.
     * The username and password should be pre-hashed using the SHA-256 algorithm for security purposes.
     * Additionally, an empty array is created to store the user's favorite teams.
     *
     * @param string $username The username for the user (SHA-256 hash).
     * @param string $password The password for the user (SHA-256 hash).
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->favTeams = array();
    }

    public function addFavTeam($teamName)
    {
        $connection = dbConnect();
        // Check if the team already exists in the teams table
        $teamId = getTeamIdByName($connection, $teamName);

        if ($teamId === null) {
            $logoReference = getImageRef($connection, $teamName);

            // Retrieve the team's nickname using an API call
            $nickname = getTeamNicknameFromAPI($teamName);

            // Insert the new team into the 'teams' table
            addNewTeam($connection, $teamName, $nickname, $logoReference);

            // Get the newly inserted team's ID
            $teamId = getTeamIdByName($connection, $teamName);
        }

        // Check if the team is already a favorite
        if (!isTeamFavorite($connection, $this->userId, $teamId)) {
            // Add the team to the user's favorites
            addUserFavoriteTeam($connection, $this->userId, $teamId);
            echo "Team '$teamName' added to favorites.";
        } else {
            echo "Team '$teamName' is already in favorites.";
        }
        $connection =null;
    }

    public function removeFavTeam($teamName)
    {
        $connection = dbConnect();

        // Check if the team exists in the teams table
        $teamId = getTeamIdByName($connection, $teamName);

        if ($teamId === null) {
            // Team doesn't exist, handle this case as needed
            echo "Team does not exist in the database.";
            return;
        }
        // Check if the team is a favorite
        if (isTeamFavorite($connection, $this->userId, $teamId)) {
            // Remove the team from the user's favorites
            removeUserFavoriteTeam($connection, $this->userId, $teamId);
            echo "Team '$teamName' removed from favorites.";
        } else {
            echo "Team '$teamName' is not in favorites.";
        }
        $connection = null;
    }


    public function getfavTeams(){
        $connection = dbConnect();
        $favoriteTeams = getUserFavTeams($connection, $this->username);
        $connection = null;
        return $favoriteTeams;
    }

    public function resetPassword($passwordResetToken,$newPassword){

    }

    public function updatePassword($oldPassword, $newPassword)
    {

    }
    public function setUserId($id){
        $this->userId = $id;
    }
    public function getEmail(){

    }
}
