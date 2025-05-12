<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function carrerUs(){
	
		try{
		return view($this->getView('pages.career'));
		}catch(Exception $e){

			return $e->getMessage();

		}
    }

    public function contactUs(){
	
		try{
		return view($this->getView('pages.contact'));
		}catch(Exception $e){

			return $e->getMessage();

		}
    }

    public function Mission(){
	
		try{
		return view($this->getView('pages.mission'));
		}catch(Exception $e){

			return $e->getMessage();

		}
    }

    public function Program(){
	
		try{
		return view($this->getView('pages.programs'));
		}catch(Exception $e){

			return $e->getMessage();

		}
    }

    public function Services(){
	
		try{
		return view($this->getView('pages.services'));
		}catch(Exception $e){

			return $e->getMessage();

		}
    }

    public function termsConditions(){
	
		try{
		return view($this->getView('pages.term-condition'));
		}catch(Exception $e){

			return $e->getMessage();

		}
    }

    public function privacyPolicy(){
	
		try{
		return view($this->getView('pages.privacy-policy'));
		}catch(Exception $e){

			return $e->getMessage();

		}
    }

}

