<?php

namespace app\behaviors;

use dosamigos\transliterator\TransliteratorHelper;
use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

class AliasBehavior extends Behavior
{
    public $in_attribute = 'name';
    public $out_attribute = 'alias';
    public $translit = true;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'getAlias'
        ];
    }

    public function getAlias()
    {
        if ( empty( $this->owner->{$this->out_attribute} ) ) {
            $this->owner->{$this->out_attribute} = $this->generateAlias( $this->owner->{$this->in_attribute} );
        } else {
            $this->owner->{$this->out_attribute} = $this->generateAlias( $this->owner->{$this->out_attribute} );
        }
    }

    private function generateAlias( $slug )
    {
        $slug = $this->slugify( $slug );
        if ( $this->checkUniqueAlias( $slug ) ) {
            return $slug;
        } else {
            for ( $suffix = 2; !$this->checkUniqueAlias( $new_slug = $slug . '-' . $suffix ); $suffix++ ) {}
            return $new_slug;
        }
    }

    private function slugify( $slug )
    {
        if ( $this->translit ) {
            return Inflector::slug( TransliteratorHelper::process( $slug ), '-', true );
        } else {
            return $this->slug( $slug, '-', true );
        }
    }

    private function slug( $string, $replacement = '-', $lowercase = true )
    {
        $string = preg_replace( '/[^\p{L}\p{Nd}]+/u', $replacement, $string );
        $string = trim( $string, $replacement );
        return $lowercase ? strtolower( $string ) : $string;
    }

    private function checkUniqueAlias( $slug )
    {
        $pk = $this->owner->primaryKey();
        $pk = $pk[0];

        $condition = $this->out_attribute . ' = :out_attribute';
        $params = [ ':out_attribute' => $slug ];
        if ( !$this->owner->isNewRecord ) {
            $condition .= ' and ' . $pk . ' != :pk';
            $params[':pk'] = $this->owner->{$pk};
        }

        return !$this->owner->find()
            ->where( $condition, $params )
            ->one();
    }
}