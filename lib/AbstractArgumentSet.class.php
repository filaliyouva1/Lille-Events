<?php
/**
* AbstractArgumentSet
* @see AbstractArgumentSet\define
* @author Bruno.Bogaert[at]univ-lille1.fr
* @copyright Bruno.Bogaert[at]univ-lille1.fr
* @license https://creativecommons.org/licenses/by-nc-nd/2.0/legalcode  Creative Commons (CC BY-NC-ND 2.0)
 *
 *
 */

/**
 * Outil de test et de filtrage d'arguments reçus via HTTP (en GET ou en POST).
 * En étendant cette classe abstraite, on réalisera une classe dédiée à l'ensemble des arguments
 * à vérifier
 *
 * Une classe concrète <strong>doit</strong> implémenter une méthode {@see AbstractArgumentSet::definitions()}
 * <pre>protected function definitions()</pre>
 * qui définit les paramètres HTTP à prendre en compte
 * <h3>Download</h3>
 * {@see http://www.fil.univ-lille1.fr/~bogaert/tw2/documents/AbstractArgumentSet/AbstractArgumentSet.zip}
*
* <h3>Exemples</h3>
* <h4>Exemple 1</h4>
* Une application veut prendre en compte les paramètres :
* <ul>
* <li>numbers : tableau d'au moins 2 entiers >=10</li>
* <li>operation : 'sum' (par défaut) ou 'product'</li>
* <li>message : chaîne quelconque, optionnelle (chaîne vide par défaut)</li>
* </ul>
 * On définit une classe appropriée :
 * <pre>
 * class ArgSetExemple1 extends AbstractArgumentSet{
 *   protected function definitions() { // impératif : def des arguments
 *      $this->defineInt('numbers', ['dimension'=>'array','min_range'=>10]);
 *      $this->defineEnum('operation', ['sum','product'], ['default'=>'sum']);
 *      $this->defineString('message');
 *   }
  * }
 * </pre>
 *
 * La validité de l'ensemble d'arguments est indiquée par la méthode <code>isValid()</code>.
 * La valeur retenue pour un argument est obtenue par <code>getValue(nom)</code> ou tout simplement
 * comme un attribut. (<code>$args->operation</code> équivaut à  <code>$args->getValue('operation')</code>)
 * Exemple :
 * <pre>
 * $args = new ArgSetExemple1(); // lit et vérifie les paramètres reçus
 * if ($args->isValid()) { // tous les paramètres sont corrects
 *     if ($args->operation == 'sum')
 *         $res = array_sum($args->numbers);
 *     else // sure it's 'product'
 *         $res = array_product($args->numbers);
 *	   echo $args->message . $res;
 * }
 * else
 *   echo "Paramètres incorrects. " . implode(', ', $args->getErrorMessages());
 * </pre>
 *********************
 * <h4 id="exemple1bis">Exemple 1 bis</h4>
 * On peut définir une méthode <pre>protected function checkings() </pre>
 * qui réalise des tests de validité supplémentaire. Son résultat doit être
 * une chaîne non vide (message d'erreur) quand le test n'est pas vérifié,
 * ou NULL (ou '') sinon.
 *
 * <code>checkings()</code> est invoquée «automatiquement» par le constructeur après les définitions mais
 * <strong>uniquement si tous les arguments sont valides</strong>.
 *
 * Par exemple, pour imposer que le tableau numbers comporte au moins 2 éléments :
* <pre>
 * class ArgSetExemple1 extends AbstractArgumentSet{
 *   protected function definitions() { // impératif : def des arguments
 *      $this->defineInt('numbers', ['dimension'=>'array','min_range'=>10]);
 *      $this->defineEnum('operation', ['sum','product'], ['default'=>'sum']);
 *      $this->defineString('message');
 *   }
 *   protected function checkings() { // méthode optionnelle (contrôles suppl.	)
 *      if (count($this->numbers)<2)
 *        return "on veut au moins 2 nombres"; // message d'erreur (non vide)
 *      else
 *	      return NULL; // tout va bien
 *   }
 * }
 * </pre>
 *********************
 * <h4 id="exemple2">Exemple 2</h4>
 *  Paramètres à définir :
 * <ul>
 * <li>op : 'add', 'clear', '' (par défaut)<br/>
 *  Si op vaut 'add' alors il faut :<ul>
 * <li>nom : chaîne non vide obligatoire</li>
 * <li>numero : entier naturel obligatoire</li>
 * </ul></li>
 * </ul>
  *
  * <pre>
  * class ArgSetExemple2 extends AbstractArgumentSet{
  *   protected function definitions(){
	*      $this->defineEnum('op', ['add','clear',''], ['default'=>'']);
	*      if ( $this->op == 'add' ) {
  *          $this->defineInt('numero', ['min_range'=>1]);
  *          $this->defineNonEmptyString('nom');
	*      }
	*   }
  * }
  * </pre>
	* <h4>Exemple 3</h4>
  *  Paramètres à définir :
  * <ul>
  * <li>ip1 et ip2, 2 adresses IP différents</li>
  * </ul>
   *
   * <pre>
   * class ArgSetExemple3 extends AbstractArgumentSet{
	 *   protected function defineIP($name,$options=[]){
	 *	   return $this->registerFiltered($name, FILTER_VALIDATE_IP, $options);
   *   }
   *   protected function definitions(){
	 *      $this->defineIP1('ip1');
	 *      $this->defineIP1('ip1');
	 *   }
	 *   protected function checkings() {
 	 *      if ( $this->ip1 == $this->ip2 )
	 * 				return "les adresses IP doivent être différentes" );
	 *      return NULL;
 	 *   }
   * }
   * </pre>
	 * <h3>Documentation</h3>
	 * <h4>Les méthodes <strong>defineXXXX()</strong></h4>
	 *	- le premier argument est toujours le nom du paramètre à tester et/ou filtrer
	 *	- le dernier argument (facultatif) permet de passer un dictionnaire d'options (table associative)
	 *
	 * Les méthodes defineXXX fournies couvrent la majorité des besoins.
	 *
	 * <h4>Options</h4>
	 * <table border="1">
	 * <tr><th colspan="2">Communes à toutes les méthodes</th></tr>
	 *  <tr><td><tt>default</tt></td><td>définit une valeur par défaut qui s'applique si le paramètre est absent (mais pas si il est erroné).
	 *    La valeur fournie doit respecter les contraintes définies pour le paramètre.</td></tr>
	 *  <tr><td><tt>dimension</tt></td><td><b>scalar</b> (défaut) ou <b>array</b></td></tr>
	 *  <tr><td><tt>case</tt></td><td><b>to_lower</b>, <b>to_upper</b>, <b>as_is</b> (défaut).
	 *       La valeur reçue est mise en minuscules ou majuscules ou laissée inchangée.
	 *        La transformation éventuelle a lieu <strong>avant</strong> le test de validité</td></tr>
	 * <tr><th colspan="2">méthode <var>defineInt()</var></th></tr>
	 * <tr><td><tt>min_range</tt></td><td>valeur minimale</td></tr>
	 * <tr><td><tt>max_range</tt></td><td>valeur maximale</td></tr>
	 * <tr><th colspan="2">méthode <var>defineFloat()</var></th></tr>
	 * <tr><td><tt>decimal</tt></td><td>caractère séparateur de la partie décimale : '.' ou ',' </td></tr>
   * </table>
	 * <h4>Écrire une nouvelle méthode defineXXX()</h4>
	 * - de nouvelles méthodes «defineXXX()» peuvent être définies <strong>HORS</strong> de la classe AbstractArgumentSet
	 * - leur visibilité doit être <tt>protected</tt>
	 * - elles doivent faire appel à la méthode <var>registerFiltered(....)</var>, seule façon d'enregistrer un nouveau paramètre
	 * - voir exemple 3 ci-dessus
  *
	* @see AbstractArgumentSet\define
  * @author Bruno.Bogaert[at]univ-lille1.fr
  * @copyright Bruno.Bogaert[at]univ-lille1.fr
  * @license https://creativecommons.org/licenses/by-nc-nd/2.0/legalcode  Creative Commons (CC BY-NC-ND 2.0)
*/
abstract class AbstractArgumentSet {
	private $inputMethod;  			// INPUT_GET, INPUT_POST, ...

	private $errorMessages = [];
  private $arguments = [];   // assoc. array : key : argument name, value : $status
	                           // status is assoc array with keys 'value', 'error', 'rawValue'

	private function addArgument($name, $status){
		if (isset($this->arguments[$name]))
			throw new Exception("argument $name allready defined");
		$this->arguments[$name] = $status;
		if ($status['error']){
			$this->errorMessages[] = "$name : {$status['error']}";
		}
	}
	private function setArgumentValue($name, $value, $rawValue){
 	  if (is_null($value))
 	 		throw new Exception('value can\'t be null');
 	  $this->addArgument($name,['value'=> $value, 'error'=> NULL, 'rawValue'=> $rawValue]);
  }
	private function rejectArgumentValue($name, $rawValue){
		$this->addArgument($name,['value'=>NULL, 'error'=>'rejected', 'rawValue'=> $rawValue]);
	}
	private function missingArgument($name){
		$this->addArgument($name,['value'=>NULL, 'error'=>'missing', 'rawValue'=> NULL]);
	}
	private function addErrors($errorMessages){
		if ($errorMessages && !is_array($errorMessages))
			$errorMessages = [$errorMessages];
		if ($errorMessages && count($errorMessages)>0)
		  array_push($this->errorMessages, ...$errorMessages);
	}

	/**
   *	Validity  of this argument set or validity of some arguments
 	* @param null|string|string[] $scope   scope
 	* @return bool
 	* if $scope is NULL : validity of this entire Argument Set <br/>
 	*	if $scope is a string : validity of argument named $scope.<br/>
 	* if $scope is a string array : validity of every argument whos name is in $scope
 	*/
 	public function isValid($scope = NULL){
 		if (is_null($scope)) // global validity
 			return count($this->errorMessages)==0;

 		if (! is_array($scope))
 			$scope = [$scope];   // scope : list of names
 		foreach ($scope as $name){
 			if ( !isset($this->arguments[$name]) || $this->arguments[$name]['error'] )
 				return FALSE;
 		}
 		return TRUE;
 	}
	/**
  	*	Get argument's elected value.
 	*	@param string $name argument name
 	*	@return mixed argument value. returns null for missing or rejected raw value
 	* @throws Exception when $name is not a defined argument
  	*/
  public final function getValue($name){
 	 if (! isset($this->arguments[$name]))
	 		throw new Exception('Unknown argument');
	 else if ($this->arguments[$name]['error'])
	    return null;
	 else
 		  return $this->arguments[$name]['value'];
  }

  /**
  	*	Argument's elected value.
 	* Allows access to argument values using pseudo property notation :<br/>
 	*  $paramSet->arg1  is an equivalent to $paramSet->getValue('arg1')
 	*	@param string $name argument name
 	*	@return mixed argument value. returns null for missing or rejected raw value
  	*/
  public final function __get($name){
 	 try {
 		 return $this->getValue($name);
 	 } catch (Exception $e){
 			 if (isset($this->{$name})){
 				 $level = E_USER_ERROR;
 				 $message = "Cannot access private or protected property ";
 			 } else {
 				 $level = E_USER_NOTICE;
 				 $message = "Undefined property ";
 			 }
 			 $caller = debug_backtrace()[0];
 			 $message .= "<b>{$caller['class']}::\${$name}</b> in <b>{$caller['file']}</b> on line <b>{$caller['line']}</b><br />\n";
 			 trigger_error($message,$level);
 	 }
 }

 /**
 * Elected values
 * @param bool $fullMode
 * @return array arguments values. Only valid arguments are returned, unless $fullMode is true
 */
	public final function getValues($fullMode = false){
		$res =[];
		foreach ($this->arguments as $name => $status) {
			if ($fullMode || !$status['error'])
					 $res[$name]=$status['value'];
		}
		return $res;
 }

 /**
 * Arguments status
 * @return array arguments status : list of associative arrays (keys : 'value', 'error', 'rawValue')
 */
	public final function getStatus(){
		return $this->arguments;
  }

 	/**
 	 *	Gets erroneous arguments list
 	 *	@return  array  associative array (map) of errors.
 	 *	 key : arg name (string), value : "rejected" or "missing"
 	 */
 	public final function getArgErrors(){
		$res =[];
		foreach ($this->arguments as $name => $status) {
			if ($status['error'])
					 $res[$name]=$status['error'];
		}
		return $res;
 	}

	/**
	 * Error messages list
	 * @return string[]
	 */
	 public final function getErrorMessages(){
		 return $this->errorMessages;
	 }

 private function prepareFilterOptions($filter, $options, $flags){
	 // add default options
	 $options = array_merge(['dimension'=>'scalar'],$options);

	 // ignore given dimension flags and replace by dimension option
	 if ($options['dimension'] =='array')
		 $result['flags'] = $flags & ~FILTER_REQUIRE_SCALAR & ~FILTER_FORCE_ARRAY | FILTER_REQUIRE_ARRAY;
	 else  // scalar
		 $result['flags'] = $flags & ~FILTER_REQUIRE_ARRAY & ~FILTER_FORCE_ARRAY | FILTER_REQUIRE_SCALAR;

	 // set filter options
	 if ($filter === FILTER_CALLBACK){  // for this filter, options must contain only callback
		 if (!isset($options['callback']))
			 throw new Exception("FILTER_CALLBACK needs a callback");
		 $result['options'] = $options['callback'];
	 }
	 else { // don't use buit-in default mechanism
		 $result['options'] = array_diff_key($options,['default'=>1]);
	 }
	 return $result;
 }

 private function filterValue($v, $filter, $filterOptions){
	 $res = filter_var($v, $filter, $filterOptions);
	 if ($filterOptions['flags'] & FILTER_REQUIRE_ARRAY){
		 return (is_array($res) && !in_array(false, $res ,true)) ?	$res : false;
	 } else {
		 return (!is_array($res) && $res !== false) ?	$res : false;
	 }

 }

/**
 * Defines a new argument, using PHP filter.
 * @param string $name Argument name
 * @param int $filter Filter {@see http://php.net/manual/fr/filter.filters.php}
 * @param string[] $options Associative array : optionName=>optionValue.
 * @param int $flags
 *
 * @return mixed|null
 */
 protected final function registerFiltered($name, $filter, $options=[], $flags=0){
	 $filterOptions = $this->prepareFilterOptions($filter, $options, $flags);
	 $default = @$options['default'];
	 if ($default !== NULL){ 	// verify default value	validity
		 $default = $this->filterValue($default, $filter, $filterOptions);
		 if ($default === FALSE)
			 throw new Exception("Incorrect default value : " . json_encode($options['default']));
	 }
	 $rawValue = filter_input($this->inputMethod, $name, FILTER_UNSAFE_RAW, $filterOptions);
	 $v = is_null($rawValue) ? $default : $rawValue; 	 // apply default value

	 $case=@$options['case'];
	 if ($case == 'to_upper')
	 		$v = mb_strtoupper($v);
	 else if ($case == 'to_lower')
	 		$v = mb_strtolower($v);

	 if (is_null($v)){
		 $this->missingArgument($name);
	 }
	 else {
		 $v = $this->filterValue($v, $filter, $filterOptions);
		 if ($v === FALSE)
		 	$this->rejectArgumentValue($name, $rawValue);
		 else
		 	$this->setArgumentValue($name, $v, $rawValue);
	 }
	 return $v;
 }



	/**
	 * Defines a new argument. Argument value can be any string and will be sanitized.
	 * for scalar argument, default value is '', unless user specifies an other default value in $options['default'].
	 * @param string $name Argument name
	 * @param string[] $options Options : 'default', 'dimension'.
	 * @return string|null Elected value (sanitized), if accepted; false if value is rejected; null if value is missing and there is no default value
	 */
	protected final function defineString($name, $options=[]){  // defaults to ''
		if (! isset($options['dimension']) || $options['dimension']=='scalar')
				$options = array_merge(['default'=>''],$options);
		return $this->registerFiltered($name,FILTER_SANITIZE_STRING,$options);
	}

	/**
	 * Defines a new argument. Argument value can be any non empty string and will be sanitized.
	 * @param string $name Argument name
	 * @param string[] $options Options : 'default', 'dimension'.
	 * @return string|null Elected value (sanitized) if accepted; false if value is rejected; null if argument is missing and there is no default value
	 */
	protected final function defineNonEmptyString($name, $options=[]){
		$options['callback']=
					function($v) {$v=filter_var($v,FILTER_SANITIZE_STRING); return ($v!='')?$v:false;} ;
		return $this->registerFiltered($name, FILTER_CALLBACK, $options);
	}
	/**
	 * Defines a new argument. Accepted values are listed in <var>$values</var>
	 * @param string $name Argument name
	 * @param string[] $values Allowed values
	 * @param string[] $options Options : 'default', 'dimension'.
	 * @return string|null Elected value if accepted; false if value is rejected; null if argument is missing and there is no default value
	 */
	protected final function defineEnum($name, array $values, $options=[]){
		$options['callback']=
							function($v) use ($values){return in_array($v,$values)?$v:false;} ;
		return $this->registerFiltered($name, FILTER_CALLBACK, $options);
	}

	/**
	 * Define a new argument. Accepted values are specified by a regular expression.
	 * @param string $name Argument name
	 * @param string $regExp Regular expression
	 * @param string[] $options Options : 'default', 'dimension'.
	 * @return string|null Elected value if accepted; false if value is rejected; null if argument is missing and there is no default value
	 */
	protected final function defineRegExp($name, $regExp, $options=[]){
		$options['regexp']= $regExp;
		return $this->registerFiltered($name, FILTER_VALIDATE_REGEXP, $options);
	}

	/**
	 * Define a new argument. Argument value must be an integer
	 * @param string $name Argument name
	 * @param string[] $options Options : 'default', 'dimension', 'min_range' (min value), 'max_range' (max value)
	 * @return integer|null Elected value if accepted; false if value is rejected; null if argument is missing and there is no default value
	 */
	protected final function defineInt($name, $options=[]){
		return $this->registerFiltered( $name, FILTER_VALIDATE_INT, $options );
	}

	/**
	 * Define a new argument. Argument value must be a number
	 * @param string $name Argument name
	 * @param string[] $options Options : 'default', 'dimension', 'decimal' (decimal separator)
	 * @return float|null Elected value if accepted; false if value is rejected; null if argument is missing and there is no default value
	 */
	protected final function defineFloat($name, $options=[]){
		return $this->registerFiltered( $name, FILTER_VALIDATE_FLOAT, $options );
	}


	/**
	* Constructor
	*	@param string|int $inputMethod : method used, given as string "get", "post", "auto" or as constant : INPUT_GET, INPUT_POST
	*/
	public final function __construct($inputMethod = "auto"){
		if (is_string($inputMethod)){ // translate string to constant value
			$method = strtoupper($inputMethod);
			if ($method=="AUTO"){ // detect method from server infos
				$method = $_SERVER['REQUEST_METHOD'];
			}
			$method = "INPUT_".$method;
			if (!defined($method)) // constant undefined
			   throw new Exception("unknown input method : $method");
			$inputMethod = constant($method); // constant value
		}
  	$this->inputMethod = $inputMethod;

		$this->definitions();

		if ($this->isValid()){ // arguments OK
      $this->addErrors($this->checkings());
		}
	}
	/**
	 *	extended classes can implement this method in order to process additionnal checkings.
	 *
	 *	@return null|false|string|string[] error message (non empty string) or error message list.
	 *  NULL or FALSE if checking is OK
	 *
	 *  checkings() is invoked by constructor when definitions() is completed, only if every declared argument is valid.
	 *  This arguments set will be tagged as invalid if result of checkings() is a non empty string or a non empty strings array.
	 */
	protected function checkings(){
		return NULL;
	}
  /**
	*	extended classes must implement this function, in order to define arguments, using defineXXX() methods
	*	@return void
	*/
	protected abstract function definitions();


}
?>
