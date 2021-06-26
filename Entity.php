<?php 

try
{
    $DB = new PDO('mysql:host=localhost;dbname=active_record', 'alex', 'password');
}
catch (PDOException $e)
{
    printf("ERROR: %s", $e->getMessage());
    die();
}    

class Entity
{
    public $ID;
    public $P;
    public $dP;
    public $ddP;
    public $Health;
    public $Damage;

    public function Save($DB, Entity $Entity) : bool
    {
        $Statement = $DB->prepare("Insert Into Entities(ID, P, dP, ddP, Health, Damage) Values (?, ?, ?, ?, ?, ?)");
        $Result = $Statement->execute(array($Entity->ID, $Entity->P, $Entity->dP, $Entity->ddP, $Entity->Health, $Entity->Damage));
        return $Result;
    }

    public function Remove($DB, Entity $Entity) : bool
    {
        $Statement = $DB->prepare("Delete From Entities Where ID = ?, P = ?, dP = ?, ddP = ?, Health = ?, Damage = ?)");
        $Result = $Statement->execute(array($Entity->ID, $Entity->P, $Entity->dP, $Entity->ddP, $Entity->Health, $Entity->Damage));
        return $Result;
    }

    public function GetById($DB, $ID) : Entity
    {
        $Statement = $DB->prepare("Select * From Entities Where ID = ?");
        $Entity = $Statement->execute(array($ID));
        $Result = new Entity;
        if(!empty($Entity))
        {
            $Result->ID     = $Entity["ID"];
            $Result->P      = $Entity["P"];
            $Result->dP     = $Entity["dP"];
            $Result->ddP    = $Entity["ddP"];
            $Result->Health = $Entity["Health"];
            $Result->Damage = $Entity["Damage"];
        }
        return $Result;
    }

    public function GetAll($DB) : array
    {
        $Result = array();
        foreach($DB->query('Select * From Entities') as $Entity)
        {
            $ToBePushed = new Entity;
            $ToBePushed->ID     = $Entity["ID"];
            $ToBePushed->P      = $Entity["P"];
            $ToBePushed->dP     = $Entity["dP"];
            $ToBePushed->ddP    = $Entity["ddP"];
            $ToBePushed->Health = $Entity["Health"];
            $ToBePushed->Damage = $Entity["Damage"];
            array_push($Result, $ToBePushed);
        }
        return $Result;
    }

    public function GetByDamage($DB, $Damage) : array
    {
        $Result = array();
        $Statement = $DB->prepare("Select * From Entities Where Damage = ?");
        foreach($Statement->execute(array($Damage)) as $Entity)
        {
            $ToBePushed = new Entity;
            $ToBePushed->ID     = $Entity["ID"];
            $ToBePushed->P      = $Entity["P"];
            $ToBePushed->dP     = $Entity["dP"];
            $ToBePushed->ddP    = $Entity["ddP"];
            $ToBePushed->Health = $Entity["Health"];
            $ToBePushed->Damage = $Entity["Damage"];
            array_push($Result, $ToBePushed);
        }
        return $Result;
    }
}
    // Use case examples
    $Entity = new Entity();
    $Entity->ID = 1;
    $Entity->P = 15.32;
    $Entity->dP = -2.3;
    $Entity->ddP = -9.81;
    $Entity->Health = 135;
    $Entity->Damage = 12;
    Entity::Save($DB, $Entity);

    $Entity = new Entity();
    $Entity->ID = 2;
    $Entity->P = 32.3;
    $Entity->dP = 2.332;
    $Entity->ddP = 0;
    $Entity->Health = 210;
    $Entity->Damage = 28;
    Entity::Save($DB, $Entity);

    $EntityArray = Entity::GetAll($DB);
    var_dump($EntityArray);

?>