<?php
namespace SiteBundle\Entity\Traits;


trait UniversalFields
{
// strings

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $string1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $string2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $string3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $string4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $string5;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $string6;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $string7;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $string8;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $string9;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $string10;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $string11;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $string12;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $string13;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $string14;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $string15;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $string16;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $string17;


    // integers

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $integer1;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $integer2;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $integer3;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $integer4;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $integer5;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $integer6;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $integer7;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $integer8;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $integer9;

    // texts

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $text1;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $text2;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $text3;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $text4;


    // images



    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image1;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image2;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image3;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image4;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image5;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image6;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image7;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image8;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image9;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $originalImage1;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $originalImage2;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $originalImage3;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $originalImage4;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $originalImage5;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $originalImage6;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $originalImage7;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $originalImage8;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $originalImage9;

    //array
    /**
     * @var array
     * @ORM\Column(type="json_array" , nullable=true)
     */
    protected $arr1;
    /**
     * @var array
     * @ORM\Column(type="json_array", nullable=true )
     */
    protected $arr2;
    /**
     * @var array
     * @ORM\Column(type="json_array", nullable=true )
     */
    protected $arr3;
    /**
     * @var array
     * @ORM\Column(type="json_array" , nullable=true)
     */
    protected $arr4;
    /**
     * @var array
     * @ORM\Column(type="json_array" , nullable=true)
     */
    protected $arr5;

    /**
     * @var bool
     * @ORM\Column(type="boolean" , nullable=true, options={"default" : 0})
     */
    protected $bool1;
    /**
     * @var bool
     * @ORM\Column(type="boolean" , nullable=true, options={"default" : 0})
     */
    protected $bool2;
    /**
     * @var bool
     * @ORM\Column(type="boolean" , nullable=true, options={"default" : 0})
     */
    protected $bool3;
    /**
     * @var bool
     * @ORM\Column(type="boolean" , nullable=true, options={"default" : 0})
     */
    protected $bool4;
    /**
     * @var bool
     * @ORM\Column(type="boolean" , nullable=true, options={"default" : 0})
     */
    protected $bool5;



    public function getString1(): ?string
    {
        return $this->string1;
    }

    public function setString1(?string $string1): self
    {
        $this->string1 = $string1;

        return $this;
    }

    public function getString2(): ?string
    {
        return $this->string2;
    }

    public function setString2(?string $string2): self
    {
        $this->string2 = $string2;

        return $this;
    }

    public function getString3(): ?string
    {
        return $this->string3;
    }

    public function setString3(?string $string3): self
    {
        $this->string3 = $string3;

        return $this;
    }

    public function getString4(): ?string
    {
        return $this->string4;
    }

    public function setString4(?string $string4): self
    {
        $this->string4 = $string4;

        return $this;
    }

    public function getString5(): ?string
    {
        return $this->string5;
    }

    public function setString5(?string $string5): self
    {
        $this->string5 = $string5;

        return $this;
    }

    public function getString6(): ?string
    {
        return $this->string6;
    }

    public function setString6(?string $string6): self
    {
        $this->string6 = $string6;

        return $this;
    }

    public function getString7(): ?string
    {
        return $this->string7;
    }

    public function setString7(?string $string7): self
    {
        $this->string7 = $string7;

        return $this;
    }

    public function getString8(): ?string
    {
        return $this->string8;
    }

    public function setString8(?string $string8): self
    {
        $this->string8 = $string8;

        return $this;
    }

    public function getString9(): ?string
    {
        return $this->string9;
    }

    public function setString9(?string $string9): self
    {
        $this->string9 = $string9;

        return $this;
    }

    public function getString10(): ?string
    {
        return $this->string10;
    }

    public function setString10(?string $string10): self
    {
        $this->string10 = $string10;

        return $this;
    }

    public function getString11(): ?string
    {
        return $this->string11;
    }

    public function setString11(?string $string11): self
    {
        $this->string11 = $string11;

        return $this;
    }

    public function getString12(): ?string
    {
        return $this->string12;
    }

    public function setString12(?string $string12): self
    {
        $this->string12 = $string12;

        return $this;
    }

    public function getString13(): ?string
    {
        return $this->string13;
    }

    public function setString13(?string $string13): self
    {
        $this->string13 = $string13;

        return $this;
    }

    public function getString14(): ?string
    {
        return $this->string14;
    }

    public function setString14(?string $string14): self
    {
        $this->string14 = $string14;

        return $this;
    }

    public function getString15(): ?string
    {
        return $this->string15;
    }

    public function setString15(?string $string15): self
    {
        $this->string15 = $string15;

        return $this;
    }

    public function getString16(): ?string
    {
        return $this->string16;
    }

    public function setString16(?string $string16): self
    {
        $this->string16 = $string16;

        return $this;
    }

    public function getString17(): ?string
    {
        return $this->string17;
    }

    public function setString17(?string $string17): self
    {
        $this->string17 = $string17;

        return $this;
    }

    public function getInteger1(): ?int
    {
        return $this->integer1;
    }

    public function setInteger1(?int $integer1): self
    {
        $this->integer1 = $integer1;

        return $this;
    }

    public function getInteger2(): ?int
    {
        return $this->integer2;
    }

    public function setInteger2(?int $integer2): self
    {
        $this->integer2 = $integer2;

        return $this;
    }

    public function getInteger3(): ?int
    {
        return $this->integer3;
    }

    public function setInteger3(?int $integer3): self
    {
        $this->integer3 = $integer3;

        return $this;
    }

    public function getInteger4(): ?int
    {
        return $this->integer4;
    }

    public function setInteger4(?int $integer4): self
    {
        $this->integer4 = $integer4;

        return $this;
    }

    public function getInteger5(): ?int
    {
        return $this->integer5;
    }

    public function setInteger5(?int $integer5): self
    {
        $this->integer5 = $integer5;

        return $this;
    }

    public function getInteger6(): ?int
    {
        return $this->integer6;
    }

    public function setInteger6(?int $integer6): self
    {
        $this->integer6 = $integer6;

        return $this;
    }

    public function getInteger7(): ?int
    {
        return $this->integer7;
    }

    public function setInteger7(?int $integer7): self
    {
        $this->integer7 = $integer7;

        return $this;
    }

    public function getInteger8(): ?int
    {
        return $this->integer8;
    }

    public function setInteger8(?int $integer8): self
    {
        $this->integer8 = $integer8;

        return $this;
    }

    public function getInteger9(): ?int
    {
        return $this->integer9;
    }

    public function setInteger9(?int $integer9): self
    {
        $this->integer9 = $integer9;

        return $this;
    }

    public function getText1(): ?string
    {
        return $this->text1;
    }

    public function setText1(?string $text1): self
    {
        $this->text1 = $text1;

        return $this;
    }

    public function getText2(): ?string
    {
        return $this->text2;
    }

    public function setText2(?string $text2): self
    {
        $this->text2 = $text2;

        return $this;
    }

    public function getText3(): ?string
    {
        return $this->text3;
    }

    public function setText3(?string $text3): self
    {
        $this->text3 = $text3;

        return $this;
    }

    public function getText4(): ?string
    {
        return $this->text4;
    }

    public function setText4(?string $text4): self
    {
        $this->text4 = $text4;

        return $this;
    }

    public function getImage1(): ?string
    {
        return $this->image1;
    }

    public function setImage1(?string $image1): self
    {
        $this->image1 = $image1;

        return $this;
    }

    public function getImage2(): ?string
    {
        return $this->image2;
    }

    public function setImage2(?string $image2): self
    {
        $this->image2 = $image2;

        return $this;
    }

    public function getImage3(): ?string
    {
        return $this->image3;
    }

    public function setImage3(?string $image3): self
    {
        $this->image3 = $image3;

        return $this;
    }

    public function getImage4(): ?string
    {
        return $this->image4;
    }

    public function setImage4(?string $image4): self
    {
        $this->image4 = $image4;

        return $this;
    }

    public function getImage5(): ?string
    {
        return $this->image5;
    }

    public function setImage5(?string $image5): self
    {
        $this->image5 = $image5;

        return $this;
    }

    public function getImage6(): ?string
    {
        return $this->image6;
    }

    public function setImage6(?string $image6): self
    {
        $this->image6 = $image6;

        return $this;
    }

    public function getImage7(): ?string
    {
        return $this->image7;
    }

    public function setImage7(?string $image7): self
    {
        $this->image7 = $image7;

        return $this;
    }

    public function getImage8(): ?string
    {
        return $this->image8;
    }

    public function setImage8(?string $image8): self
    {
        $this->image8 = $image8;

        return $this;
    }

    public function getImage9(): ?string
    {
        return $this->image9;
    }

    public function setImage9(?string $image9): self
    {
        $this->image9 = $image9;

        return $this;
    }

    public function getOriginalImage1(): ?string
    {
        return $this->originalImage1;
    }

    public function setOriginalImage1(?string $originalImage1): self
    {
        $this->originalImage1 = $originalImage1;

        return $this;
    }

    public function getOriginalImage2(): ?string
    {
        return $this->originalImage2;
    }

    public function setOriginalImage2(?string $originalImage2): self
    {
        $this->originalImage2 = $originalImage2;

        return $this;
    }

    public function getOriginalImage3(): ?string
    {
        return $this->originalImage3;
    }

    public function setOriginalImage3(?string $originalImage3): self
    {
        $this->originalImage3 = $originalImage3;

        return $this;
    }

    public function getOriginalImage4(): ?string
    {
        return $this->originalImage4;
    }

    public function setOriginalImage4(?string $originalImage4): self
    {
        $this->originalImage4 = $originalImage4;

        return $this;
    }

    public function getOriginalImage5(): ?string
    {
        return $this->originalImage5;
    }

    public function setOriginalImage5(?string $originalImage5): self
    {
        $this->originalImage5 = $originalImage5;

        return $this;
    }

    public function getOriginalImage6(): ?string
    {
        return $this->originalImage6;
    }

    public function setOriginalImage6(?string $originalImage6): self
    {
        $this->originalImage6 = $originalImage6;

        return $this;
    }

    public function getOriginalImage7(): ?string
    {
        return $this->originalImage7;
    }

    public function setOriginalImage7(?string $originalImage7): self
    {
        $this->originalImage7 = $originalImage7;

        return $this;
    }

    public function getOriginalImage8(): ?string
    {
        return $this->originalImage8;
    }

    public function setOriginalImage8(?string $originalImage8): self
    {
        $this->originalImage8 = $originalImage8;

        return $this;
    }

    public function getOriginalImage9(): ?string
    {
        return $this->originalImage9;
    }

    public function setOriginalImage9(?string $originalImage9): self
    {
        $this->originalImage9 = $originalImage9;

        return $this;
    }

    public function getArr1()
    {
        return $this->arr1;
    }

    public function setArr1($arr1): self
    {
        $this->arr1 = $arr1;

        return $this;
    }

    public function getArr2()
    {
        return $this->arr2;
    }

    public function setArr2($arr2): self
    {
        $this->arr2 = $arr2;

        return $this;
    }

    public function getArr3()
    {
        return $this->arr3;
    }

    public function setArr3($arr3): self
    {
        $this->arr3 = $arr3;

        return $this;
    }

    public function getArr4()
    {
        return $this->arr4;
    }

    public function setArr4($arr4): self
    {
        $this->arr4 = $arr4;

        return $this;
    }

    public function getArr5()
    {
        return $this->arr5;
    }

    public function setArr5($arr5): self
    {
        $this->arr5 = $arr5;

        return $this;
    }

    public function getBool1(): ?bool
    {
        return $this->bool1;
    }

    public function setBool1(?bool $bool1): self
    {
        $this->bool1 = $bool1;

        return $this;
    }

    public function getBool2(): ?bool
    {
        return $this->bool2;
    }

    public function setBool2(?bool $bool2): self
    {
        $this->bool2 = $bool2;

        return $this;
    }

    public function getBool3(): ?bool
    {
        return $this->bool3;
    }

    public function setBool3(?bool $bool3): self
    {
        $this->bool3 = $bool3;

        return $this;
    }

    public function getBool4(): ?bool
    {
        return $this->bool4;
    }

    public function setBool4(?bool $bool4): self
    {
        $this->bool4 = $bool4;

        return $this;
    }

    public function getBool5(): ?bool
    {
        return $this->bool5;
    }

    public function setBool5(?bool $bool5): self
    {
        $this->bool5 = $bool5;

        return $this;
    }
}