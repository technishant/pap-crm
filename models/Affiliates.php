<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "affiliates".
 *
 * @property integer $id
 * @property string $external_id
 * @property string $firstname
 * @property string $lastname
 * @property string $username
 * @property string $refid
 * @property string $rstatus
 * @property string $street
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $zipcode
 * @property string $phone
 * @property string $photo
 * @property string $created
 * @property string $updated
 * @property integer $is_deleted
 * @property string $crm_id
 */
class Affiliates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'affiliates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['external_id', 'firstname', 'username'], 'required'],
            [['created', 'updated'], 'safe'],
            [['is_deleted'], 'integer'],
            [['external_id'], 'string', 'max' => 45],
            [['firstname', 'lastname', 'username', 'refid', 'rstatus', 'street', 'city', 'state', 'country', 'zipcode', 'phone', 'photo'], 'string', 'max' => 255],
            [['external_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'external_id' => 'PAP Reference',
            'firstname' => 'First Name',
            'lastname' => 'Last Name',
            'username' => 'Username',
            'refid' => 'Referral ID',
            'rstatus' => 'Status',
            'street' => 'Street',
            'city' => 'City',
            'state' => 'State',
            'country' => 'Country',
            'zipcode' => 'Zipcode',
            'phone' => 'Phone',
            'photo' => 'Photo',
            'created' => 'Created',
            'updated' => 'Updated',
            'is_deleted' => 'Is Deleted',
			'crm_id' => 'CRM Reference'
        ];
    }

    public function mapAffiliateToModel($affiliate){
		$this->firstname = $affiliate->get('firstname');
		$this->lastname = $affiliate->get('lastname');
		$this->external_id = $affiliate->get('id');
		$this->username = $affiliate->get('username');
		$this->refid = $affiliate->get('refid');
		$this->rstatus = $affiliate->get('rstatus');
		$this->city = $affiliate->get('data4');
		$this->state = $affiliate->get('data5');
		$this->street = $affiliate->get('refid');
		$this->country = $affiliate->get('data6');
		$this->zipcode = $affiliate->get('data7');
		$this->phone = $affiliate->get('data8');
	}

	public function mapModelToCrm(){
    	return json_encode([
    		"properties" => [
    			["name" => "first_name", "value" => $this->firstname, "type" => "SYSTEM"],
				["name" => "last_name", "value" => $this->lastname, "type" => "SYSTEM"],
				["name" => "email", "value" => $this->username, "type" => "SYSTEM"],
				["name" => "email", "value" => $this->username, "type" => "SYSTEM"],
				["name" => "phone", "value" => $this->phone, "type" => "SYSTEM"],
				["name" => "address", "value" => json_encode(["address" => $this->street, "city" => $this->city, "state" => $this->state, "country" => $this->country]), "type" => "SYSTEM"],
			]
		]);
	}

	public function sync(){
    	$model = Affiliates::find()->where('external_id = :external_id', [':external_id' => $this->external_id])->one();
    	if($model == null){
			$this->isNewRecord = true;
			if(!$this->save()){
				return $this->getErrors();
			}
		} else {
    		$model->attributes = $this->attributes;
			if(!$model->save()){
				return $model->getErrors();
			}
		}
		return true;
	}

	public function getName(){
    	return $this->firstname." ".$this->lastname;
	}
}
