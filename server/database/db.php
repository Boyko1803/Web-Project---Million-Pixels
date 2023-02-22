<?php
    class Database {
        private $connection;

        public function __construct() {
            $config = parse_ini_file('../../configuration/serverConfig.ini', true);

            $type = $config['db']['db_type'];
            $host = $config['db']['host'];
            $name = $config['db']['db_name'];
            $user = $config['db']['user'];
            $password = $config['db']['password'];

            $this->connection = new PDO("$type:host=$host;dbname=$name", $user, $password);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
		
		public function insertUserQuery($data) {
            $sql = "INSERT INTO users(fn, name, email, password, is_participant) VALUES (:fn, :name, :email, :password, :is_participant)";
            $insertUser = $this->connection->prepare($sql);
			
			try {
                $insertUser->execute($data);

                return ["success" => true];
            } catch(Exception $e) {
                return ["success" => false, "error" => "Неуспешна заявка"];
            }
        }
		
		public function insertParticipantQuery($data) {
            $sql = "INSERT INTO participants(fn, points, grade) VALUES (:fn, :points, :grade)";
            $insertUser = $this->connection->prepare($sql);
			
			try {
                $insertUser->execute($data);

                return ["success" => true];
            } catch(Exception $e) {
                return ["success" => false, "error" => "Неуспешна заявка"];
            }
        }
		
		public function insertPictureQuery($data) {
            $sql = "INSERT INTO pictures(creator_fn, points_cost, x_start, x_end, y_start, y_end, picture_name, link, text) VALUES (:creator_fn, :points_cost, :x_start, :x_end, :y_start, :y_end, :picture_name, :link, :text)";
            $insertPicture = $this->connection->prepare($sql);
			
			try {
                $insertPicture->execute($data);

                return ["success" => true];
            } catch(Exception $e) {
                return ["success" => false, "error" => "Неуспешна заявка"];
            }
        }
		
		public function selectUserQuery($data) {
            $sql = "SELECT * FROM users WHERE fn = :fn";
			$selectUser = $this->connection->prepare($sql);
			
			try {
                $selectUser->execute($data);
                
                return ["success" => true, "data" => $selectUser];
            } catch(Exception $e) {
                return ["success" => false, "error" => "Неуспешна заявка"];
            }
        }
		
		public function selectPictureQuery($data) {
            $sql = "SELECT * FROM pictures WHERE id = :id";
			$selectPicture = $this->connection->prepare($sql);
			
			try {
                $selectPicture->execute($data);
                
                return ["success" => true, "data" => $selectPicture];
            } catch(Exception $e) {
                return ["success" => false, "error" => "Неуспешна заявка"];
            }
        }
		
		public function selectParticipantQuery($data) {
            $sql = "SELECT * FROM participants WHERE fn = :fn";
			$selectParticipant = $this->connection->prepare($sql);
			
			try {
                $selectParticipant->execute($data);
                
                return ["success" => true, "data" => $selectParticipant];
            } catch(Exception $e) {
                return ["success" => false, "error" => "Неуспешна заявка"];
            }
        }
		
		public function selectParticipantsQuery() {
            $sql = "SELECT * FROM participants";
			$selectParticipant = $this->connection->prepare($sql);
			
			try {
                $selectParticipant->execute();
                
                return ["success" => true, "data" => $selectParticipant];
            } catch(Exception $e) {
                return ["success" => false, "error" => "Неуспешна заявка"];
            }
        }
		
		public function selectNondeletedPicturesQuery() {
			$sql = "SELECT * FROM pictures WHERE deleted IS NULL";
			$selectPictures = $this->connection->prepare($sql);
			
			try {
				$selectPictures->execute();
				
				return ["success" => true, "data" => $selectPictures];
			} catch(Exception $e) {
				return ["success" => false, "error" => "Неуспешна заявка"];
			}
		}
		
		public function selectNondeletedPicturesWithBlockingQuery() {
			$sql = "SELECT * FROM pictures WHERE deleted IS NULL FOR UPDATE";
			$selectPictures = $this->connection->prepare($sql);
			
			try {
				$selectPictures->execute();
				
				return ["success" => true, "data" => $selectPictures];
			} catch(Exception $e) {
				return ["success" => false, "error" => "Неуспешна заявка"];
			}
		}
		
		public function updateParticipantAllowanceQuery($data) {
			$sql = "UPDATE participants SET forbidden_to_participate = :forbidden_to_participate WHERE fn = :fn";
			$updateParticipant = $this->connection->prepare($sql);
			
			try {
                $updateParticipant->execute($data);
                
				return ["success" => true];
            } catch(Exception $e) {
                return ["success" => false, "error" => "Неуспешна заявка"];
            }
		}
		
		public function updateParticipantPoints($data) {
			$sql = "UPDATE participants SET points = :points WHERE fn = :fn";
			$updateParticipant = $this->connection->prepare($sql);
			
			try {
                $updateParticipant->execute($data);
                
				return ["success" => true];
            } catch(Exception $e) {
                return ["success" => false, "error" => "Неуспешна заявка"];
            }
		}
		
		public function updatePictureAsDeletedQuery($data) {
			$sql = "UPDATE pictures SET deleted = NOW() WHERE id = :id";
			$updatePicture = $this->connection->prepare($sql);
			
			try {
                $updatePicture->execute($data);
                
				return ["success" => true];
            } catch(Exception $e) {
                return ["success" => false, "error" => "Неуспешна заявка"];
            }
		}
		
		public function deleteParticipantQuery($data) {
			$sql = "DELETE FROM participants WHERE fn = :fn";
			$deleteParticipant = $this->connection->prepare($sql);
			
			try {
                $deleteParticipant->execute($data);
                
				return ["success" => true];
            } catch(Exception $e) {
                return ["success" => false, "error" => "Неуспешна заявка"];
            }
		}
		
		public function calculateUsedPointsCostQuery($data) {
			$sql = "SELECT SUM(points_cost) FROM pictures WHERE creator_fn = :fn AND pictures.deleted IS NULL";
			$calculatePoints = $this->connection->prepare($sql);
			
			try {
                $calculatePoints->execute($data);
				$sum = $calculatePoints->fetch(PDO::FETCH_ASSOC);
				$sum = $sum["SUM(points_cost)"];
				if (is_null($sum)) $sum = 0;
                
				return ["success" => true, "sum" => $sum];
            } catch(Exception $e) {
                return ["success" => false, "error" => "Неуспешна заявка"];
            }
		}
		
		public function beginTransaction() {
			$this->connection->beginTransaction();
		}
		
		public function commit() {
			$this->connection->commit();
		}
		
		public function rollback() {
			$this->connection->rollBack();
		}

        function __destruct() {
            $this->connection = null;
        }
    }
?>
