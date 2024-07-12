<?php
include_once 'User-authentication-functions.php';
include_once 'User.php';
/**
 * Fetches a list of team names from a database connection.
 *
 * This function retrieves team names from a database table named 'teams' and returns them as an array.
 *
 * @param PDO $connection A PDO database connection.
 * @param null $searchTerm term for searching for teams in the teams table of Teams page
 * @return array An array of team names.
 */
function fetchTeamNames($connection, $searchTerm = null) {
    if ($searchTerm) {
        // If a search term is provided, only fetch matching team names
        $sql = "SELECT name FROM teams WHERE name LIKE ? ORDER BY name";
        $stmt = $connection->prepare($sql);
        $stmt->execute(["%$searchTerm%"]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // If no search term is provided, fetch all team names
        $sql = "SELECT name FROM teams ORDER BY name";
        $result = $connection->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    $teams = [];
    foreach ($result as $row) {
        $teams[] = $row["name"];
    }

    return $teams;
}

/**
 * Retrieves the image reference for a given team's logo from a database connection.
 *
 * This function queries a database table named 'teams' to find the logo reference for a specific team.
 * If the team name is not found in the database, a default logo reference is returned.
 *
 * @param PDO $connection A PDO database connection.
 * @param string $teamName The name of the team for which the logo reference is requested.
 * @return string|null The logo reference URL or null if an error occurs.
 */
function getImageRef($connection, $teamName) {

    try {
        $query = "SELECT logo_reference FROM teams WHERE name = :teamName";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':teamName', $teamName, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result !== false) {
            $opponentImageRef = $result['logo_reference'];
        } else {
            $opponentImageRef = '../images/sportsJSON/collegeFootball/logos/default-logo.png';
        }
        return $opponentImageRef;
    } catch (PDOException $e) {
        echo "Error fetching logo reference: " . $e->getMessage();
        return null;
    }
}

/**
 * Get Team Nickname
 *
 * This function retrieves the nickname of a team from the database based on the team's name.
 *
 * @param PDO $connection A PDO database connection.
 * @param string $teamName The name of the team for which to retrieve the nickname.
 *
 * @return string|null The team's nickname if found; an empty string if not found or an error occurs.
 */
function getTeamNickName($connection, $teamName){
    try {
        $query = "SELECT nickname FROM teams WHERE name = :teamName";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':teamName', $teamName, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result !== false) {
            $nickName = $result['nickname'];
        } else {
            $nickName = ' ';
        }
        return $nickName;
    } catch (PDOException $e) {
        echo "Error fetching logo reference: " . $e->getMessage();
        return null;
    }
}


// User table queries
/**
 * Check if Email Exists
 *
 * This function checks if an email address exists in the database.
 *
 * @param PDO $connection A PDO database connection.
 * @param string $email The email address to check.
 *
 * @return bool Returns `true` if the email address exists in the database; otherwise, returns `false`.
 */
function emailExists($connection, $email){
    $query = "SELECT COUNT(*) as count FROM users WHERE email = :email";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && $result['count'] > 0) {
        return true;
    } else {
        return false;
    }
}

/**
 * Insert User
 *
 * This function inserts a new user record into the database with the provided user information.
 *
 * @param PDO $connection A PDO database connection.
 * @param string $fullname The full name of the user.
 * @param string $emailHash The hashed email address of the user.
 * @param string $passwordHash The hashed password of the user.
 * @param string $salt The salt used for password hashing.
 * @param string $validationToken The validation token for the user's email verification.
 * @param int $tokenExpiration The expiration timestamp of the validation token.
 *
 * @return void
 */
function insertUser($connection, $fullname, $emailHash, $passwordHash, $salt, $validationToken, $tokenExpiration) {
    try {
        $query = "INSERT INTO users (name, email, password_hash, password_salt, verification_token, verification_token_expiration,verified)
                  VALUES (:fullname, :email, :passwordHash, :salt, :validationToken, :tokenExpiration,:verified)";
        $verified = false;
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $stmt->bindParam(':email', $emailHash, PDO::PARAM_STR);
        $stmt->bindParam(':passwordHash', $passwordHash, PDO::PARAM_STR);
        $stmt->bindParam(':salt', $salt, PDO::PARAM_STR);
        $stmt->bindParam(':validationToken', $validationToken, PDO::PARAM_STR);
        $stmt->bindParam(':tokenExpiration', $tokenExpiration, PDO::PARAM_STR);
        $stmt->bindParam(':verified', $verified, PDO::PARAM_BOOL);

        $stmt->execute();
        $stmt->closeCursor(); // Optional, but can be used to release the connection

        // Optionally, you can return the last inserted ID if needed
        return true;
    } catch (PDOException $e) {
       return  false;
    }
}

function updateUserVerification($connection, $emailHash)
{
    try {
        $query = "UPDATE users SET verified = 1 WHERE email = :emailHash";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':emailHash', $emailHash, PDO::PARAM_STR);
        $stmt->execute();

        // Check if any rows were affected (user updated)
        if ($stmt->rowCount() > 0) {
            // Return a success message or code if needed
            return true;
        } else {
            // Return an error message or code if the user was not found or already verified
            return false;
        }
    } catch (PDOException $e) {
        // Handle the exception if the update query fails
        echo "Error updating user verification: " . $e->getMessage();
        return false; // Return an error code or message
    }
}

/**
 * Get User by Validation Token
 *
 * This function retrieves user information and the validation token's expiration time
 * from the database based on the provided validation token.
 *
 * @param PDO $connection A PDO database connection.
 * @param string $token The validation token to search for.
 *
 * @return array|null An associative array containing user information and token expiration time if found;
 *                    `null` if not found or an error occurs.
 */
function getUserByToken($connection, $token)
{
    try {
        $query = "SELECT * FROM users WHERE verification_token = ? OR reset_token = ?";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(1, $token, PDO::PARAM_STR);
        $stmt->bindParam(2, $token, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user;
    } catch (PDOException $e) {
        echo "Error fetching user by token: " . $e->getMessage();
        return null;
    }
}

/**
 * Get User by ID
 * @param $connection PDO database connection
 * @param $id user id
 * @return mixed|null user information if found; null if not found or an error occurs
 */
function getUserById($connection, $id)
{
    try {
        $query = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user;
    } catch (PDOException $e) {
        echo "Error fetching user by token: " . $e->getMessage();
        return null;
    }
}

/**
 * Set a reset token for a user
 * @param $connection PDO database connection
 * @param $token reset token to be stored in the database
 * @param $tokenExpiration expiration time of the token
 * @param $emailHash hashed email address of the user
 * @return bool true if the token was set successfully; false otherwise
 */
function setResetToken($connection, $token, $tokenExpiration, $emailHash) {
    try {
        $query = "UPDATE users SET reset_token_expiration = :tokenExpiration, reset_token = :token WHERE email = :emailHash";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':tokenExpiration', $tokenExpiration, PDO::PARAM_STR);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->bindParam(':emailHash', $emailHash, PDO::PARAM_STR);

        $stmt->execute();

        $rowCount = $stmt->rowCount();
        return $rowCount > 0;
    } catch (PDOException $e) {
        echo "Error updating reset token: " . $e->getMessage();
        return false;
    }
}

function setVerificationToken($connection, $token, $tokenExpiration, $emailHash) {
    try {
        $query = "UPDATE users SET verification_token_expiration = :tokenExpiration, verification_token = :token WHERE email = :emailHash";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':tokenExpiration', $tokenExpiration, PDO::PARAM_STR);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->bindParam(':emailHash', $emailHash, PDO::PARAM_STR);

        $stmt->execute();

        $rowCount = $stmt->rowCount();
        return $rowCount > 0;
    } catch (PDOException $e) {
        echo "Error updating reset token: " . $e->getMessage();
        return false;
    }
}

/**
 * function to update a user's password when they forgot their password
 * @param $connection PDO database connection
 * @param $hashedEmail hashed email address of the user
 * @param $newPassword new password to be hashed and stored in the database
 * @return bool true if the password was updated successfully; false otherwise
 * @throws \PHPMailer\PHPMailer\Exception if the email fails to send
 */
function updatePassword($connection, $hashedEmail, $newPassword)
{
    $newSalt = generateSalt();
    $passwordHash = hash("sha256", $newPassword . $newSalt);
    try {
        $query = "UPDATE users SET password_hash = :passwordHash, password_salt = :newSalt WHERE email = :hashedEmail";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':passwordHash', $passwordHash, PDO::PARAM_STR);
        $stmt->bindParam(':newSalt', $newSalt, PDO::PARAM_STR);
        $stmt->bindParam(':hashedEmail', $hashedEmail, PDO::PARAM_STR);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function updateUserPassword($connection, $userId, $newPassword)
{
    $newSalt = generateSalt();
    $passwordHash = hash("sha256", $newPassword . $newSalt);
    try {
        $query = "UPDATE users SET password_hash = :passwordHash, password_salt = :newSalt WHERE user_id = :userId";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':passwordHash', $passwordHash, PDO::PARAM_STR);
        $stmt->bindParam(':newSalt', $newSalt, PDO::PARAM_STR);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Validate User
 *
 * This function validates a user's credentials against the database. Then creates a user object
 * to return
 *
 * @param PDO $connection A PDO database connection.
 * @param string $username The user's username (email).
 * @param string $password The user's password.
 *
 * @return array|bool An associative array containing user information if valid; `false` if invalid.
 */
function validateUser($connection, $username, $password) {
    try {
        $username = hash("sha256", $username);
        $query = "SELECT * FROM users WHERE email = :username";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $storedSalt = $result['password_salt'];
            $storedHashedPassword = $result['password_hash'];
            $hashedPassword = hash("sha256",$password. $storedSalt);
            if ($hashedPassword === $storedHashedPassword) {
                return $result;
            }
        }

        return false;
    } catch (PDOException $e) {
        echo "Error validating user: " . $e->getMessage();
        return false;
    }
}

/**
 * Get User Favorite Teams
 *
 * This function retrieves a user's favorite teams from the database.
 *
 * @param PDO $connection A PDO database connection.
 * @param string $username The user's username (email).
 *
 * @return array An array of the user's favorite teams.
 */
function getUserFavTeams($connection, $username) {
    try {
        $queryUserId = "SELECT user_id FROM users WHERE name = :username";
        $stmtUserId = $connection->prepare($queryUserId);
        $stmtUserId->bindParam(':username', $username, PDO::PARAM_STR);
        $stmtUserId->execute();
        $userId = $stmtUserId->fetch(PDO::FETCH_ASSOC)['user_id'];

        $query = "SELECT teams.name AS team_name, teams.logo_reference
                  FROM user_teams
                  INNER JOIN teams ON user_teams.team_id = teams.team_id
                  WHERE user_teams.user_id = :userId";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $favTeams = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $favTeams;
    } catch (PDOException $e) {
        echo "Error fetching user's favorite teams: " . $e->getMessage();
        return null;
    }
}

/**
 * Add Favorite Team
 *
 * This function adds a team to a user's list of favorite teams.
 *
 * @param PDO $connection A PDO database connection.
 * @param string $username The user's username (email).
 * @param string $teamName The name of the team to add.
 *
 * @return bool Returns `true` if the team was added successfully; otherwise, returns `false`.
 */
function addUserFavoriteTeam($connection, $username, $teamName)
{
    try {
        $queryUserId = "SELECT user_id FROM users WHERE name = :username";
        $stmtUserId = $connection->prepare($queryUserId);
        $stmtUserId->bindParam(':username', $username, PDO::PARAM_STR);
        $stmtUserId->execute();
        $userId = $stmtUserId->fetch(PDO::FETCH_ASSOC)['user_id'];

        $queryTeamId = "SELECT team_id FROM teams WHERE name = :teamName";
        $stmtTeamId = $connection->prepare($queryTeamId);
        $stmtTeamId->bindParam(':teamName', $teamName, PDO::PARAM_STR);
        $stmtTeamId->execute();
        $teamId = $stmtTeamId->fetch(PDO::FETCH_ASSOC)['team_id'];

        $query = "INSERT INTO user_teams (user_id, team_id) VALUES (:userId, :teamId)";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':teamId', $teamId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error adding favorite team: " . $e->getMessage();
        return false;
    }
}

/**
 * Function to get the team's ID by its name from the database.
 *
 * @param PDO $connection A PDO database connection.
 * @param string $teamName The name of the team.
 * @return int|null The team's ID or null if not found.
 * @throws PDOException if a database error occurs.
 */
function getTeamIdByName($connection, $teamName)
{
    $sql = "SELECT team_id FROM teams WHERE name = :teamName";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':teamName', $teamName, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result !== false && isset($result['team_id'])) {
        return (int)$result['team_id'];
    } else {
        return null;
    }
}

/**
 * Function to add a new team to the database.
 *
 * @param PDO $connection A PDO database connection.
 * @param string $teamName The name of the team.
 * @param string $nickname The nickname (mascot) of the team.
 * @param string $logoReference The logo reference for the team.
 * @return bool True on success, false on failure.
 */
function addNewTeam($connection, $teamName, $nickname, $logoReference)
{
    try {
        $sql = "INSERT INTO teams (name, nickname, logo_reference) VALUES (:teamName, :nickname, :logoReference)";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':teamName', $teamName, PDO::PARAM_STR);
        $stmt->bindParam(':nickname', $nickname, PDO::PARAM_STR);
        $stmt->bindParam(':logoReference', $logoReference, PDO::PARAM_STR);

        $result = $stmt->execute();
        return $result;
    } catch (PDOException $e) {
        echo "Error adding new team: " . $e->getMessage();
        return false;
    }
}

/**
 * Function to check if a team is a favorite of a specific user.
 *
 * @param PDO $connection A PDO database connection.
 * @param int $userId The user's ID.
 * @param int $teamId The team's ID.
 * @return bool True if the team is a favorite of the user, false otherwise.
 */
function isTeamFavorite($connection, $userId, $teamId)
{
    try {
        $sql = "SELECT COUNT(*) FROM user_teams WHERE user_id = :userId AND team_id = :teamId";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':teamId', $teamId, PDO::PARAM_INT);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return $count > 0;
    } catch (PDOException $e) {
        echo "Error checking if team is a favorite: " . $e->getMessage();
        return false;
    }
}

/**
 * Function to remove a team from a user's favorites.
 *
 * @param PDO $connection A PDO database connection.
 * @param int $userId The user's ID.
 * @param int $teamId The team's ID to remove from favorites.
 * @return bool True on success, false on failure.
 */
function removeUserFavoriteTeam($connection, $userId, $teamId)
{
    try {
        $sql = "DELETE FROM user_teams WHERE user_id = :userId AND team_id = :teamId";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':teamId', $teamId, PDO::PARAM_INT);

        $result = $stmt->execute();
        return $result;
    } catch (PDOException $e) {
        echo "Error removing team from favorites: " . $e->getMessage();
        return false;
    }
}


