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

class Suivi{

    private $_id;
    private $_id_livre;
    private $_demande;
    private $_pret;
    private $_retour;
    private $_id_emprunteur;

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
    public function getId_livre(){
        return $this->_id_livre;
    }
    public function getDemande(){
        return $this->_demande;
    }
    public function getPret(){
        return $this->_pret;
    }
    public function getRetour(){
        return $this->_retour;
    }
    public function getId_emprunteur(){
        return $this->_id_emprunteur;
    }

    // SETTERS
    public function setId($id){
        $this->_id = $id;
    }
	public function setId_livre($id_livre){
		$this->_id_livre = $id_livre;
    }
	public function setDemande($demande){
		$this->_demande = $demande;
    }
    public function setPret($pret){
        $this->_pret = $pret;
    }
    public function setRetour($retour){
        $this->_retour = $retour;
    }
    public function setId_emprunteur($id_emprunteur){
		$this->_id_emprunteur = $id_emprunteur;
    }

	public function ParsData($pattern, $document, $pattern1, $number){
        // Pars function to extract data from the source code of the page
        preg_match_all($pattern, $document, $matches);
        foreach($matches as $val){
            $donnees = $val[$number];
            if($pattern1 != '##'){
                $donnees = preg_replace($pattern1,'',$donnees);
            }
        }
        return $donnees;
    }

    public function accent($str){
        // Encoding problems could be resolved with this function (UTF_8/ISO)
		// $str = htmlentities($str, ENT_COMPAT, 'UTF-8');
		$str = html_entity_decode($str);
        return $str;
    }

    // Instancie la connexion
    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }

    // Get a book from the database and hydrate the object which ask this function
    public function get($id)
    {
        $id = (int) $id;

        $q = $this->_db->query('SELECT * FROM emprunts WHERE id = '.$id);
        $donnees = $q->fetch(PDO::FETCH_ASSOC);
		if(!empty($donnees['id'])){
			$this->hydrate($donnees);
		}
    }
	
    public static function getEmprunteur($db, $idLivre)
    {
		$emprunteur = new Membre($db);
        $stmt = $db->prepare('SELECT id_emprunteur FROM emprunts WHERE id_livre = :livre and retour is null');
		$stmt->bindParam(':livre', $idLivre, PDO::PARAM_INT);
		$stmt->execute();
        $donnees = $stmt->fetch(PDO::FETCH_ASSOC);
		if(!empty($donnees['id_emprunteur'])){
			$emprunteur->get($donnees['id_emprunteur']);
		}
		return $emprunteur;
    }
	
	public static function getDemandeur($db, $idLivre)
    {
		$emprunteur = new Membre($db);
        $stmt = $db->prepare('SELECT id_emprunteur FROM emprunts WHERE id_livre = :livre and pret is null');
		$stmt->bindParam(':livre', $idLivre, PDO::PARAM_INT);
		$stmt->execute();
        $donnees = $stmt->fetch(PDO::FETCH_ASSOC);
		if(!empty($donnees['id_emprunteur'])){
			$emprunteur->get($donnees['id_emprunteur']);
		}
		return $emprunteur;
    }
	
	// Delete a book from the database
    public static function deleteFromDb($db, $id)
    {
        $q = $db->exec('DELETE FROM emprunts WHERE id = '.$id);
    }
	
	
	// Return the complete list (array) of the book that are in the database
    public static function getList($db, $idLivre)
    {
        $emprunts = array();
		
		$q = $db->query('SELECT * FROM emprunts WHERE id_livre = '.$idLivre.' ORDER BY id DESC');
		
		while($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
			$emprunt = new Suivi($db);
			$emprunt->hydrate($donnees);
            $emprunts[] = $emprunt;
		}

        return $emprunts;
    }

	public static function truncateDb($db)
	{
	
		$q = $db->exec('TRUNCATE TABLE emprunts');
		return $db->errorInfo();
	
	}
}

?>