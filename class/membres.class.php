<?php
/*

   Copyright (c) 2012/2013, Bruno Desprez
   
   Permission is hereby granted, free of charge, to any person
   obtaining a copy of this software and associated documentation
   files (the "Software"), to deal in the Software without
   restriction, including without limitation the rights to use,
   copy, modify, merge, publish, distribute, sublicense, and/or sell
   copies of the Software, and to permit persons to whom the
   Software is furnished to do so, subject to the following
   conditions:
   
   The above copyright notice and this permission notice shall be
   included in all copies or substantial portions of the Software.
   
   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
   EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
   OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
   NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
   HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
   WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
   FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
   OTHER DEALINGS IN THE SOFTWARE.

*/

class Membre{

    private $_id;
    private $_nom;
    private $_prenom;
    private $_email;
    private $_actif;

    private $_db; // Instance de PDO
	
	private static $_erreur = 'img/erreur.gif';

    public function __construct($db)
    {
        $this->setDb($db);
    }


    public function hydrate(array $donnees){
        foreach ($donnees as $key => $value){
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)){
                $this->$method($value);
            }
        }
    }

    // GETTERS
    public function getId(){
        return $this->_id;
    }
    public function getNom(){
        return $this->_nom;
    }
    public function getPrenom(){
        return $this->_prenom;
    }
    public function getEmail(){
        return $this->_email;
    }
    public function getActif(){
        return $this->_actif;
    }
    
    // SETTERS
    public function setId($id){
        $this->_id = $id;
    }
    
	public function setNom($nom){
        if(is_string($nom) && strlen($nom) >= 2){
            $this->_nom = $nom;
        }
    }
  
	public function setPrenom($prenom){
        if(is_string($prenom) && strlen($prenom) >= 2){
            $this->_prenom = $prenom;
        }
    }

    public function setEmail($email){
        $this->_email = $email;
    }

    public function setActif($actif){
        $this->_actif = $actif;
    }
	

    public function accent($str){
        // Encoding problems could be resolved with this function (UTF_8/ISO)
		// $str = htmlentities($str, ENT_COMPAT, 'UTF-8');
		$str = html_entity_decode($str);
        return $str;
    }

    // Add a new member to the database
    public function add()
	{
        $q = $this->_db->prepare('INSERT INTO `emprunteur` ( `id` , `nom` , `prenom` , `email` , `actif` ) VALUES ("", :nom, :prenom, :email, :actif)');

        $q->bindValue(':nom', $this->_nom);
        $q->bindValue(':prenom', $this->_prenom);
        $q->bindValue(':email', $this->_email);
		$q->bindValue(':actif', $this->_actif, PDO::PARAM_BOOL);

        $q->execute() OR DIE('<br /><span style="color: red; font-weight: bold;">An error occured while adding the book in the database...');

        // Add the ID value from the database to the object
		$this->_id = $this->_db->lastInsertId('id');
    }

	// Update a member to the database
    public function update()
    {
        $q = $this->_db->prepare('
            UPDATE emprunteur SET
                nom = :nom,
				prenom  = :prenom,
				email = :email,
				actif = :actif
            WHERE id = :id');

        $q->bindValue(':nom', $this->_nom);
        $q->bindValue(':prenom', $this->_prenom);
        $q->bindValue(':email', $this->_email);
		$q->bindValue(':actif', $this->_actif, PDO::PARAM_BOOL);
		$q->bindValue(':id', $this->_id, PDO::PARAM_INT);

        $q->execute();
    }

    // Instancie la connexion
    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }

    // Get a membre from the database and hydrate the object which ask this function
    public function get($id)
    {
        $id = (int) $id;

        $q = $this->_db->query('SELECT * FROM emprunteur WHERE id = '.$id);
        $donnees = $q->fetch(PDO::FETCH_ASSOC);
		if(!empty($donnees['id'])){
			$this->hydrate($donnees);
		}
    }
	
	// Delete a book from the database
    public static function deleteFromDb($db, $id)
    {
        $q = $db->exec('DELETE FROM emprunteur WHERE id = '.$id);
    }
	
	
	// Return the complete list (array) of the membres that are in the database
    public static function getList($db)
    {
        $membres = array();
	
        $q = $db->query('SELECT * FROM emprunteur ORDER BY id');

        while($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $membre = new Membre($db);
			$membre->hydrate($donnees);
            $membres[] = $membre;
        }

        return $membres;
    }
	
	// Return the of the last membre inserted
	public static function getLast($db)
	{
		$array = array();
        $q = $db->query('SELECT id FROM emprunteur ORDER BY id');
        while($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $array[] = $donnees['id'];
        }
		if(!empty($array[0])){
			return max($array);
		}
	}
	
	// Return the list of x last membre inserted
	public static function getListLast($db, $number)
	{
		$array = array();
		$q = $db->query('SELECT * FROM emprunteur ORDER BY date DESC LIMIT 0,'.$number);
		while($donnees = $q->fetch(PDO::FETCH_ASSOC)){
			$membre = new Membre($db);
			$membre->hydrate($donnees);
			$array[] = $membre;
		}
		
		return $array;
	}
	
	// Check if the selected id is really in the database
	public static function isExist($db, $id)
	{
		$id = (int) $id;
		$membres = array();
        $q = $db->query('SELECT id FROM emprunteur ORDER BY id');
        while($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $membre = new Membre($db);
			$membre->hydrate($donnees);
            $membres[] = $membre;
        }
		$array = array();
		for($i=0;$i<count($membres);$i++){
			$array[$i] = $membres[$i]->getId();
		}
		return (in_array($id, $array)) ? (true) : (false);
	}
	
	
	// Return the Member by his email
	public static function getByEmail($db, $email)
	{
		$array = array();
        $q = $db->prepare('SELECT * FROM emprunteur WHERE email = ?');
		$q->execute(array($email));
        while($donnees = $q->fetch())
		{
			$membre = new Membre($db);
			$membre->hydrate($donnees);
			$array[] = $membre;
		}
		if(!empty($array[0])){
			return max($array);
		}
	}
	
	public static function demandeDispoExist($db, $emprunteur, $livre)
	{
		$q = $db->prepare('SELECT id FROM demande_dispo WHERE id_livre = ? AND id_emprunteur = ? AND averti = 0');
		$q->execute(array($livre, $emprunteur));
		return $q->fetch();
	}
	
	public static function demandePretExist($db, $emprunteur, $livre)
	{
		$q = $db->prepare('SELECT id FROM emprunts WHERE id_livre = ? AND id_emprunteur = ? AND pret is null');
		$q->execute(array($livre, $emprunteur));
		return $q->fetch();
	}
	
	public static function addDemandeDispo($db, $emprunteur, $livre)
	{
		if(!(Membre::demandeDispoExist($db, $emprunteur, $livre))){
		
			$q = $db->prepare('
				INSERT INTO demande_dispo SET
				id_livre = :id_livre,
				averti  = :averti,
				id_emprunteur = :id_emprunteur
			');

			$q->bindValue(':id_livre', $livre, PDO::PARAM_INT);
			$q->bindValue(':id_emprunteur', $emprunteur, PDO::PARAM_INT);
			$q->bindValue(':averti', 0, PDO::PARAM_BOOL);

			$q->execute() OR DIE('<br /><span style="color: red; font-weight: bold;">An error occured while adding the book in the database...');
		}
	}
	
	public static function avertirDispo($db, $livre)
	{	
        $q = $db->prepare('SELECT emprunteur.email as email FROM emprunteur INNER JOIN demande_dispo on (demande_dispo.id_emprunteur = emprunteur.id) WHERE demande_dispo.averti = 0 AND emprunteur.actif = 1 AND demande_dispo.id_livre = ?');
		$q->execute(array($livre->getId()));
        while($donnees = $q->fetch())
		{
			envoi_mail($donnees['email'], 'Bibliothèque NAC Aïkido',  'Livre disponible', 'Comme vous l\'avez demandé, nous vous informons que le livre : "'.$livre->getTitle().'" est de nouveau disponible.');
		}
		
		$q = $db->prepare('UPDATE demande_dispo SET averti = 1 WHERE id_livre = ?');
		$q->execute(array($livre->getId()));
	}
	
	public static function addDemandePret($db, $emprunteur, $livre)
	{
		if(!(Membre::demandePretExist($db, $emprunteur, $livre))){
		
			$q = $db->prepare('
				INSERT INTO emprunts SET
				id_livre = :id_livre,
				demande  = :demande,
				id_emprunteur = :id_emprunteur
			');

			$q->bindValue(':id_livre', $livre, PDO::PARAM_INT);
			$q->bindValue(':id_emprunteur', $emprunteur, PDO::PARAM_INT);
			$q->bindValue(':demande', date("Y-m-d"));

			$q->execute() OR DIE('<br /><span style="color: red; font-weight: bold;">An error occured while adding the book in the database...');
			
			$q = $db->query('SELECT email FROM isbn_admin WHERE id = 1');
			$q = $q->fetchAll();
			
			$email = $q[0]['email'];
			
			$membre = new Membre($db);
			$membre->get($emprunteur);
			
			$book = new Livre($db);
			$book->get($livre);
			$book->setId_etat(3);
			$book->update();
			
			$name = strip_tags($membre->getNom()) . " " . strip_tags($membre->getPrenom());
			
			$message = '<html><body>';
			$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
			$message .= "<tr style='background: #eee;'><td><strong>Emprunteur :</strong> </td><td>" . $name . "</td></tr>";
			$message .= "<tr><td><strong>Email :</strong> </td><td>" . strip_tags($membre->getEmail()) . "</td></tr>";         
			$message .= "<tr><td><strong>Livre :</strong> </td><td>" . strip_tags($book->getTitle()) . "</td></tr>";
			$message .= "</table>";
			$message .= "</body></html>";
			
			envoi_mail($email, $name,  'Demande de prêt', $message);
		}
	}

	public static function truncateDb($db)
	{
		$q = $db->exec('TRUNCATE TABLE demande_dispo');
		$q = $db->exec('TRUNCATE TABLE emprunts');
		$q = $db->exec('TRUNCATE TABLE emprunteur');
		return $db->errorInfo();
	
	}
}

?>