<?php

namespace ElectrikaAPI;

class Product {
  public $attributes,
         $datasheets,
         $name,
         $ID;

  private $product_data;

  public function __construct($product_data) {
    $this->product_data = $product_data;
    $this->set_product_values();
  }

  private function set_product_values() {
    $this->ID = $this->product_data['ID'];
    $this->name = (object) [
      'code' => $this->product_data['Name'],
      'short' => $this->attribute_value_for('KOSN_Short_Name'),
      'common' => $this->attribute_value_for('KOSN_Common_Code_Short_Name')
    ];
    $this->attributes = (object) [
      'description' => (object) [
        'short' => $this->attribute_value_for('KOSN_Short_Description'),
        'long' => $this->attribute_value_for('KOSN_Long_Description')
      ],
      'images' => (object) [
        'small' => (object) [
          'url' => $this->attribute_value_for('EC_ProductImage1_Small'),
          'alt' => $this->image_alt_text()
        ],
        'medium' => (object) [
          'url' => $this->attribute_value_for('EC_ProductImage1_Medium'),
          'alt' => $this->image_alt_text()
        ],
        'large' => (object) [
          'url' => $this->attribute_value_for('EC_ProductImage1_Large'),
          'alt' => $this->image_alt_text()
        ],
        'tech1' => (object) [
          'url' => $this->attribute_value_for('EC_TechnicalImage2'),
          'alt' => $this->image_alt_text()
        ],
        'tech2' => (object) [
          'url' => $this->attribute_value_for('EC_TechnicalImage3'),
          'alt' => $this->image_alt_text()
        ],
        'tech3' => (object) [
          'url' => $this->attribute_value_for('EC_TechnicalImage4'),
          'alt' => $this->image_alt_text()
        ],
      ],
           //Kloc Addition - Added all the available downloads - This gets cycled through on product single page on the downloads tab. 
           'pdf' => $this->attribute_value_for('EC_PDFCataloguePage'),
           // 'useful_lumens' => $this->attribute_value_for('KOSN_Useful_Lumens', 'N/A'),
           // 'total_lumens' => $this->attribute_value_for('KOSN_Total_Lumens', 'N/A'),
           // 'colour_finish' => $this->attribute_value_for('KOSN_Colour_Finish', 'N/A'),
           'flipbook' => $this->attribute_value_for('EC_FlipbookPage'),
           'accessories' => $this->attribute_value_for('EC_PDFDoc_Accessories'),
           'application_guide' => $this->attribute_value_for('EC_PDFDoc_Applications_Guide'),
           'brochure' => $this->attribute_value_for('EC_PDFDoc_Brochure'),
           'dimensions' => $this->attribute_value_for('EC_PDFDoc_Dimensions'),
           'finishes' => $this->attribute_value_for('EC_PDFDoc_Finishes'),
           'install_instructions' => $this->attribute_value_for('EC_PDFDoc_Install_Instructions'),
           'order_codes' => $this->attribute_value_for('EC_PDFDoc_Order_Codes'),
           'overview' => $this->attribute_value_for('EC_PDFDoc_Overview'),
           'product_catalogue' => $this->attribute_value_for('EC_PDFDoc_Product_Catalogue'),
           'reference_guide' => $this->attribute_value_for('EC_PDFDoc_Reference_Guide'),
           'selection_charts' => $this->attribute_value_for('EC_PDFDoc_Selection_Charts'),
           'tech_and_dimensions' => $this->attribute_value_for('EC_PDFDoc_Technical_&_Dimensions'),
           'tech_data' => $this->attribute_value_for('EC_PDFDoc_Technical_Data'),
           'tech_data_and_dimensions' => $this->attribute_value_for('EC_PDFDoc_Technical_Data_&_Dimensions'),
           'test_cert' => $this->attribute_value_for('EC_PDFDoc_Test_Certificate'),
           'price_list' => $this->attribute_value_for('EC_PDFDoc_Trade_Price_List'),
           'typical_applications' => $this->attribute_value_for('EC_PDFDoc_Typical_Applications'),
           'photometric_ldt' => $this->attribute_value_for('KOSN_Photometric_LDT'),
           'instructions_guide' => $this->attribute_value_for('KOSN_Instructions_PDF'),
         ];

    $this->datasheets = (object) [
      'features' => $this->populate_datasheet_for('Features'),
      'specifications' => $this->populate_datasheet_for('Specifications')
    ];
  }

  public function background_image_url($size, $use_placeholder = false) {
    $image_url = $this->image_url($size, $use_placeholder);

    return "background-image: url('$image_url');";
  }

  public function page_url() {
    $slug_from_name = sanitize_title($this->name->code);
    $site_url = site_url('/products');

    return "$site_url/$slug_from_name-{$this->ID}";
  }

  public function image_url($size, $use_placeholder = false) {
    $image_url = $this->attributes->images->{$size}->url;

    if(!empty($image_url)) return $image_url;

    if($use_placeholder) return PUBLIC_FOLDER . '/images/kosnic-placeholder.jpg';

    return false;
  }

  private function image_alt_text() {
    return "{$this->name->code} Product Image";
  }

  private function attribute_value_for($field_title, $empty_value = false) {
    $attributes_array = $this->product_data['Attributes'];

    if(!is_array($attributes_array)) return $empty_value;

    $attribute_array_index = array_search(
      $field_title,
      array_column($attributes_array, 'Title')
    );

    if(!empty($attribute_array_index)) {
      return $attributes_array[$attribute_array_index]['Value'];
    }

    return $empty_value;
  }

  private function populate_datasheet_for($group_title) {
    $datasheets = $this->product_data['Datasheets'];

    for($i = 0; $i < count($datasheets); $i++) {
      if($datasheets[$i]['Group']['GroupTitle'] === $group_title) {
        return $this->format_datasheet_values($datasheets[$i]['NodeAttributes']);
      }
    }

    return false;
  }

  private function format_datasheet_values($datasheet_arrays) {
    $datasheet_values = [];

    for($i = 0; $i < count($datasheet_arrays); $i++) {
      $datasheet_array_desc = $datasheet_arrays[$i]['NodeAttributeTitle']['Description'];
      $array_key = !empty($datasheet_array_desc) ? $datasheet_array_desc : $i;
      $datasheet_values[$array_key] = $datasheet_arrays[$i]['NodeAttributeValue']['Value'];
    }

    return $datasheet_values;
  }
}
