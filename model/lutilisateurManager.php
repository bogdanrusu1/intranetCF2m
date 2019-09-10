<?php

class lutilisateurManager
{
	
	private $db;
	
	public function __construct(MyPDO $connect) {
		$this->db = $connect;
	}
	
	public function connectLutilisateur(lutilisateur $user): bool {
		$sql = "
		SELECT
			lutilisateur.lenomutilisateur AS login, lutilisateur.lemotdepasse AS pwd, lerole.idlerole AS role
		FROM
			lutilisateur

		LEFT JOIN lutilisateur_has_lerole ON lutilisateur.idlutilisateur = lutilisateur_has_lerole.lutilisateur_idutilisateur
		LEFT JOIN lerole ON lerole.idlerole = lutilisateur_has_lerole.lerole_idlerole
		WHERE lutilisateur.lenomutilisateur = :login " . "
		LIMIT 1;"; // LIMIT 1 tant que l'on utilise un utilisateur ne peut avoir qu'un rôle
		$sqlQuery = $this->db->prepare($sql);
		$sqlQuery->bindValue(":login", $user->getLenomutilisateur(), PDO::PARAM_STR);
		$sqlQuery->execute();
		
		$result = $sqlQuery->fetch(PDO::FETCH_ASSOC);
		if($user->getLenomutilisateur() == $result['login'] && password_verify($user->getLemotdepasse(), $result['pwd'])) {
			$_SESSION['TheIdSess'] = session_id();
			$_SESSION['login'] = $result['login'];
			$_SESSION['role'] = $result['role'];
			return True;
		} else {
			return False;
		}
	}
	
	public function lutilisateurDisplayContent(): array {
		$sql = "
		DESCRIBE
			lutilisateur;";
		$sqlQuery = $this->db->prepare($sql);
		$sqlQuery->execute();
		
		return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function lutilisateurSelectById(lutilisateur $user): array {
		$sql = "
		SELECT
			*
		FROM
			lutilisateur
		WHERE
			idlutilisateur = :id;";
		$sqlQuery = $this->db->prepare($sql);
		$sqlQuery->bindValue(":id", $user->getIdutilisateur(), PDO::PARAM_INT);
		$sqlQuery->execute();
		
		return $sqlQuery->fetch(PDO::FETCH_ASSOC);
	}
	
	public function lutilisateurUpdate(lutilisateur $user, array $datas) {
		$updateDatas = "";
		foreach($datas as $dataField => $data) {
			$updateDatas .= $dataField . " = '" . $data . "', ";
		}
		$updateDatas = substr($updateDatas, 0, -2);
	
		$sql = "
		UPDATE
			lutilisateur
		SET
			" . $updateDatas . "
		WHERE
			idlutilisateur = :id;";
		$sqlQuery = $this->db->prepare($sql);
		$sqlQuery->bindValue(":id", $user->getIdutilisateur(), PDO::PARAM_INT);
		$sqlQuery->execute();
	}

	public function lutilisateurDelete(lutilisateur $user): void {
		$sql = "
		DELETE
		FROM
			lutilisateur
		WHERE
			idlutilisateur = :id;";
		$sqlQuery = $this->db->prepare($sql);
		$sqlQuery->bindValue(":id", $user->getIdutilisateur(), PDO::PARAM_INT);
		$sqlQuery->execute();
	}
	

	
	public function lutilisateurSelectAll(): array {	
		$sql = "
		SELECT
			*
		FROM
			lutilisateur";
		$sqlQuery = $this->db->prepare($sql);
		$sqlQuery->execute();
		
		return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function lutilisateurSelectAllJoinLerole(string $joinType = "inner"): array {
		$joinType = strtolower($joinType);
		if($joinType !== "inner" && $joinType !== "left" && $joinType !== "right") {return [];}
	
		$sql = "
		SELECT
			lutilisateur.idlutilisateur AS lutilisateur_idlutilisateur, lutilisateur.lenomutilisateur AS lutilisateur_lenomutilisateur, lutilisateur.lemotdepasse AS lutilisateur_lemotdepasse, lutilisateur.lenom AS lutilisateur_lenom, lutilisateur.leprenom AS lutilisateur_leprenom, lutilisateur.lemail AS lutilisateur_lemail, lutilisateur.luniqueid AS lutilisateur_luniqueid, lutilisateur_has_lerole.lutilisateur_idutilisateur AS lutilisateur_has_lerole_lutilisateur_idutilisateur, lutilisateur_has_lerole.lerole_idlerole AS lutilisateur_has_lerole_lerole_idlerole, lerole.idlerole AS lerole_idlerole, lerole.lintitule AS lerole_lintitule, lerole.ladescription AS lerole_ladescription
		FROM
			lutilisateur
		" . $joinType . " JOIN lutilisateur_has_lerole ON lutilisateur.idlutilisateur = lerole_idlerole
		" . $joinType . " JOIN lerole ON lerole.idlerole = lutilisateur_idutilisateur;";
		$sqlQuery = $this->db->prepare($sql);
		$sqlQuery->execute();
		
		return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
	}

}