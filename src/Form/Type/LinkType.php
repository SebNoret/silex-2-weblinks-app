<?php

namespace WebLinks\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;


class LinkType extends AbstractType {
       
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    
    {
        $builder->add('title', TextType::class);
        $builder->add('url', UrlType::class);

        
    }
    public function getName()
    {
        return 'link';
    }
}
