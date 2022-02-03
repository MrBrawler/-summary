<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Subcategory;
use App\Product;
use App\Brand;
use App\Shape;
use App\Symptom;

use Illuminate\Support\Facades\DB;


class CatalogController extends Controller
{
    

    public function catalog(Request $request)
    {
        $brands = Brand::all();
        $shapes = Shape::all();
        $dataBrand = $request->input('chk_name');
        $categories = DB::select('select * from categories');

        $products = Product::all();
        return view('catalog', ['shapes' => $shapes, 'categories' => $categories, 'brands' => $brands, 'dataBrand' => $dataBrand, 'products' => $products]);

    }

    public function showCategory($slug)
    {
        //$categoryId = Category::where('slug', $slug)->first()->id;
        $categoryId = Category::where('slug', $slug)->first();
        if($categoryId) {
            $categoryId = $categoryId->id;
            $categoriesWithSub = Category::where('slug', $slug)->with('subcategories')->get();
            $brands = Brand::all();
            $categories = DB::select('select * from categories');
            $shapes = Shape::all();

            $data = array(
                'slug' => $slug,
                'id' => $categoryId
            );
            $products = Category::with('products')->findOrFail($data['id'])->products;
            $catName = Category::with('products')->findOrFail($data['id']);

            if (isset($_GET['cBtn'])) {
                //Выбран бренд, форма и сортировка по цене
                if ((!empty($_GET['chk_name']) && !empty($_GET['chk_name2'])) && ($_GET['orderby'] == "price" || $_GET['orderby'] == "price-desc")) {
                    $products = Category::with('products')->findOrFail($data['id'])->products
                        ->whereIn('brand_id', array_values($_GET['chk_name']))
                        ->whereIn('shape_id', array_values($_GET['chk_name2']));

                    if ($_GET['orderby'] == "price") {
                        return view('category', ['data' => $data, 'catName' => $catName, 'categoriesWithSub' => $categoriesWithSub, 'shapes' => $shapes, 'categories' => $categories, 'brands' => $brands, 'dataShape' => $_GET['chk_name2'], 'dataBrand' => $_GET['chk_name'], 'products' => $products->sortBy('price')]);
                    } elseif ($_GET['orderby'] == "price-desc") {
                        return view('category', ['data' => $data, 'catName' => $catName, 'categoriesWithSub' => $categoriesWithSub, 'shapes' => $shapes, 'categories' => $categories, 'brands' => $brands, 'dataShape' => $_GET['chk_name2'], 'dataBrand' => $_GET['chk_name'], 'products' => $products->sortByDesc('price')]);
                    }
                    return view('category', ['data' => $data, 'catName' => $catName, 'categoriesWithSub' => $categoriesWithSub, 'shapes' => $shapes, 'categories' => $categories, 'brands' => $brands, 'dataShape' => $_GET['chk_name2'], 'dataBrand' => $_GET['chk_name'], 'products' => $products]);
                    //Не выбран бренд и форма, выбрана сортировка по цене
                } elseif ((empty($_GET['chk_name']) && empty($_GET['chk_name2'])) && ($_GET['orderby'] == "price" || $_GET['orderby'] == "price-desc")) {
                    if ($_GET['orderby'] == "price") {
                        return view('category', ['data' => $data, 'catName' => $catName, 'categoriesWithSub' => $categoriesWithSub, 'shapes' => $shapes, 'categories' => $categories, 'brands' => $brands, 'products' => $products->sortBy('price')]);
                    } elseif ($_GET['orderby'] == "price-desc") {
                        return view('category', ['data' => $data, 'catName' => $catName, 'categoriesWithSub' => $categoriesWithSub, 'shapes' => $shapes, 'categories' => $categories, 'brands' => $brands, 'products' => $products->sortByDesc('price')]);
                    }
                    return view('category', ['data' => $data, 'catName' => $catName, 'categoriesWithSub' => $categoriesWithSub, 'shapes' => $shapes, 'categories' => $categories, 'brands' => $brands, 'products' => $products]);
                    //Не выбран бренд, выбрана форма и сортировка по цене
                } elseif ((empty($_GET['chk_name']) && !empty($_GET['chk_name2'])) && ($_GET['orderby'] == "price" || $_GET['orderby'] == "price-desc")) {
                    $products = Category::with('products')->findOrFail($data['id'])->products
                        ->whereIn('shape_id', array_values($_GET['chk_name2']));

                    if ($_GET['orderby'] == "price") {
                        return view('category', ['data' => $data, 'catName' => $catName, 'categoriesWithSub' => $categoriesWithSub, 'shapes' => $shapes, 'categories' => $categories, 'brands' => $brands, 'dataShape' => $_GET['chk_name2'], 'products' => $products->sortBy('price')]);
                    } elseif ($_GET['orderby'] == "price-desc") {
                        return view('category', ['data' => $data, 'catName' => $catName, 'categoriesWithSub' => $categoriesWithSub, 'shapes' => $shapes, 'categories' => $categories, 'brands' => $brands, 'dataShape' => $_GET['chk_name2'], 'products' => $products->sortByDesc('price')]);
                    }
                    return view('category', ['shapes' => $shapes, 'categories' => $categories, 'brands' => $brands, 'dataShape' => $_GET['chk_name2'], 'products' => $products]);
                    //Выбран бренд и сортировка по цене ,не выбрана форма
                } elseif ((!empty($_GET['chk_name']) && empty($_GET['chk_name2'])) && ($_GET['orderby'] == "price" || $_GET['orderby'] == "price-desc")) {
                    $products = Category::with('products')->findOrFail($data['id'])
                        ->products
                        ->whereIn('brand_id', array_values($_GET['chk_name']));
                    if ($_GET['orderby'] == "price") {
                        return view('category', ['data' => $data, 'catName' => $catName, 'categoriesWithSub' => $categoriesWithSub, 'shapes' => $shapes, 'categories' => $categories, 'brands' => $brands, 'dataBrand' => $_GET['chk_name'], 'products' => $products->sortBy('price')]);
                    } elseif ($_GET['orderby'] == "price-desc") {
                        return view('category', ['data' => $data, 'catName' => $catName, 'categoriesWithSub' => $categoriesWithSub, 'shapes' => $shapes, 'categories' => $categories, 'brands' => $brands, 'dataBrand' => $_GET['chk_name'], 'products' => $products->sortByDesc('price')]);
                    }
                    return view('category', ['shapes' => $shapes, 'categories' => $categories, 'brands' => $brands, 'dataBrand' => $_GET['chk_name'], 'products' => $products]);
                    //Не выбран бренд и форма, выбрана сортировка по цене
                }
            } else {

                return view('category', ['shapes' => $shapes, 'data' => $data, 'catName' => $catName, 'categoriesWithSub' => $categoriesWithSub, 'categories' => $categories, 'brands' => $brands, 'products' => $products]);
            }
        }else {
            return view('layouts.404',[
                'title' => 'Категория не найдена',
                'message'   => 'Категория не найдена',
                'code'      => '404',
                'link' => 'catalog'
            ]);
        }

    }

    public function showSubCategory($slug, $subSlug)
    {
        function getUnique($products){
            $uniqueKey = array();
            $productsArr = array();
            foreach ($products as $product){
                if(!in_array($product->name,$uniqueKey)){
                    $uniqueKey[]=$product->name;
                    $productsArr[]=$product;
                }
            }
            return $productsArr;
        }
        $subcategory = Subcategory::where('slug', $subSlug)->get();
        $parentCategory = Category::where('slug', $slug)->first()->name;
        $symptoms = Symptom::all();
        $brands = Brand::all();
        $categories = DB::select('select * from categories');
        $shapes = Shape::all();
        //$symptomProduct = App\Symptom::with('products')->findOrFail($_GET['chk_name3']);
        foreach ($subcategory as $subcat) {
            $data = array(
                'slug' => $slug,
                'subslug' => $subSlug,
                'subcategory' => $subcategory,
                'subcatId' => $subcat->sub_id,
            );
        }
        $products = Subcategory::with('products')->findOrFail($data['subcatId'])->products;

        if(isset($_GET['cBtnSub'])){

            //Выбран бренд, симптом, форма и сортировка по цене
            if(!empty($_GET['chk_name3']) && ((!empty($_GET['chk_name']) && !empty($_GET['chk_name2'])) && ($_GET['orderby'] == "price" || $_GET['orderby'] == "price-desc"))){

                $symptoms = Symptom::with('products')->findOrFail($_GET['chk_name3']);
                $products = DB::table('products')
                    ->leftJoin('symptom_product', 'products.id', '=', 'symptom_product.product_id')
                    ->rightJoin('subcategory_product', 'products.id', '=', 'subcategory_product.product_id')
                    ->get();
                    $products = $products
                        ->whereIn('symptom_id', array_values($_GET['chk_name3']))
                        ->whereIn('subcat_id', $data['subcatId'])
                        ->whereIn('brand_id', array_values($_GET['chk_name']))
                        ->whereIn('shape_id', array_values($_GET['chk_name2']));

                    if($_GET['orderby'] == "price"){
                        return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'data' => $data, 'shapes' => $shapes,'categories' => $categories,  'brands' => $brands,'dataShape' => $_GET['chk_name2'], 'dataBrand' => $_GET['chk_name'], 'products' => getUnique($products->sortBy('price'))]);
                    }elseif($_GET['orderby'] == "price-desc"){
                        return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'data' => $data,  'shapes' => $shapes,'categories' => $categories,  'brands' => $brands,'dataShape' => $_GET['chk_name2'], 'dataBrand' => $_GET['chk_name'], 'products' => getUnique($products->sortByDesc('price'))]);
                    }

                return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'data' => $data,  'shapes' => $shapes,'categories' => $categories,  'brands' => $brands,'dataShape' => $_GET['chk_name2'], 'dataBrand' => $_GET['chk_name'], 'products' => getUnique($products)]);
                //Не выбран бренд,симптом и форма, выбрана сортировка по цене
            }elseif(empty($_GET['chk_name3']) && ((empty($_GET['chk_name']) && empty($_GET['chk_name2'])) && ($_GET['orderby'] == "price" || $_GET['orderby'] == "price-desc"))){
                if($_GET['orderby'] == "price"){
                    return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'data' => $data,  'shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'products' => $products->sortBy('price')]);
                }elseif($_GET['orderby'] == "price-desc"){
                    return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'data' => $data,  'shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'products' => $products->sortByDesc('price')]);
                }
                return view('subcategory', ['symptoms' => $symptoms, 'parentCategory' => $parentCategory,'data' => $data,  'shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'products' => $products]);
                //Не выбран бренд и симптом, выбрана форма и сортировка по цене
            }elseif(empty($_GET['chk_name3']) && ((empty($_GET['chk_name']) && !empty($_GET['chk_name2'])) && ($_GET['orderby'] == "price" || $_GET['orderby'] == "price-desc"))){
                $products = $products
                    ->whereIn('shape_id', array_values($_GET['chk_name2']));

                if($_GET['orderby'] == "price"){
                    return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'data' => $data, 'shapes' => $shapes,'categories' => $categories,  'brands' => $brands,'dataShape' => $_GET['chk_name2'], 'products' => $products->sortBy('price')]);
                }elseif($_GET['orderby'] == "price-desc"){
                    return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'data' => $data, 'shapes' => $shapes,'categories' => $categories,  'brands' => $brands,'dataShape' => $_GET['chk_name2'], 'products' => $products->sortByDesc('price')]);
                }
                return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'shapes' => $shapes,'categories' => $categories,  'brands' => $brands,'dataShape' => $_GET['chk_name2'], 'products' => $products]);
                //Выбран бренд и сортировка по цене ,не выбрана форма и симптом
            }elseif(empty($_GET['chk_name3']) && ((!empty($_GET['chk_name']) && empty($_GET['chk_name2'])) && ($_GET['orderby'] == "price" || $_GET['orderby'] == "price-desc"))){
                $products = Subcategory::with('products')->findOrFail($data['subcatId'])->products
                    ->whereIn('brand_id', array_values($_GET['chk_name']));
                if($_GET['orderby'] == "price"){
                    return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'data' => $data, 'shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'dataBrand' => $_GET['chk_name'], 'products' => $products->sortBy('price')]);
                }elseif($_GET['orderby'] == "price-desc"){
                    return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'data' => $data, 'shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'dataBrand' => $_GET['chk_name'], 'products' => $products->sortByDesc('price')]);
                }
                return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'dataBrand' => $_GET['chk_name'], 'products' => $products]);
                //Выбрана форма, бренд и сортировка по цене ,не выбран симптом
            }elseif(empty($_GET['chk_name3']) && ((!empty($_GET['chk_name']) && !empty($_GET['chk_name2'])) && ($_GET['orderby'] == "price" || $_GET['orderby'] == "price-desc"))){
                $products = Subcategory::with('products')->findOrFail($data['subcatId'])->products
                    ->whereIn('brand_id', array_values($_GET['chk_name']))
                    ->whereIn('shape_id', array_values($_GET['chk_name2']));
                if($_GET['orderby'] == "price"){
                    return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'data' => $data, 'shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'dataBrand' => $_GET['chk_name'],'dataShape' => $_GET['chk_name2'], 'products' => $products->sortBy('price')]);
                }elseif($_GET['orderby'] == "price-desc"){
                    return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'data' => $data, 'shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'dataBrand' => $_GET['chk_name'],'dataShape' => $_GET['chk_name2'], 'products' => $products->sortByDesc('price')]);
                }
                return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'dataBrand' => $_GET['chk_name'],'dataShape' => $_GET['chk_name2'], 'products' => $products]);
                //Выбран  бренд, симптом и сортировка по цене ,не выбрана форма
            }elseif(!empty($_GET['chk_name3']) && ((!empty($_GET['chk_name']) && empty($_GET['chk_name2'])) && ($_GET['orderby'] == "price" || $_GET['orderby'] == "price-desc"))){
                $symptoms = Symptom::with('products')->findOrFail($_GET['chk_name3']);
                $products = DB::table('products')
                    ->leftJoin('symptom_product', 'products.id', '=', 'symptom_product.product_id')
                    ->rightJoin('subcategory_product', 'products.id', '=', 'subcategory_product.product_id')
                    ->get();
                $products = $products
                    ->whereIn('subcat_id', $data['subcatId'])
                    ->whereIn('symptom_id', array_values($_GET['chk_name3']))
                    ->whereIn('brand_id', array_values($_GET['chk_name']));
                if($_GET['orderby'] == "price"){
                    return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'data' => $data, 'shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'dataBrand' => $_GET['chk_name'], 'products' => getUnique($products->sortBy('price'))]);
                }elseif($_GET['orderby'] == "price-desc"){
                    return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'data' => $data, 'shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'dataBrand' => $_GET['chk_name'], 'products' => getUnique($products->sortByDesc('price'))]);
                }
                return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'dataBrand' => $_GET['chk_name'], 'products' => getUnique($products)]);
                // Не выбран  бренд, выбран симптом, сортировка по цене и форма
            }elseif(!empty($_GET['chk_name3']) && ((empty($_GET['chk_name']) && !empty($_GET['chk_name2'])) && ($_GET['orderby'] == "price" || $_GET['orderby'] == "price-desc"))){
                $symptoms = Symptom::with('products')->findOrFail($_GET['chk_name3']);
                $products = DB::table('products')
                    ->leftJoin('symptom_product', 'products.id', '=', 'symptom_product.product_id')
                    ->rightJoin('subcategory_product', 'products.id', '=', 'subcategory_product.product_id')
                    ->get();
                $products = $products
                    ->whereIn('subcat_id', $data['subcatId'])
                    ->whereIn('symptom_id', array_values($_GET['chk_name3']))
                    ->whereIn('shape_id', array_values($_GET['chk_name2']));
                if($_GET['orderby'] == "price"){
                    return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'data' => $data, 'shapes' => $shapes,'categories' => $categories,  'brands' => $brands,  'products' => getUnique($products->sortBy('price'))]);
                }elseif($_GET['orderby'] == "price-desc"){
                    return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'data' => $data, 'shapes' => $shapes,'categories' => $categories,  'brands' => $brands,  'products' => getUnique($products->sortByDesc('price'))]);
                }
                return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'products' => getUnique($products)]);
                // Не выбран  бренд и форма,  выбран симптом и сортировка по цене
            }elseif(!empty($_GET['chk_name3']) && ((empty($_GET['chk_name']) && empty($_GET['chk_name2'])) && ($_GET['orderby'] == "price" || $_GET['orderby'] == "price-desc"))){
                $symptoms = Symptom::with('products')->findOrFail($_GET['chk_name3']);
                $products = DB::table('products')
                    ->leftJoin('symptom_product', 'products.id', '=', 'symptom_product.product_id')
                    ->rightJoin('subcategory_product', 'products.id', '=', 'subcategory_product.product_id')
                    ->get();
                $products = $products
                    ->whereIn('subcat_id', $data['subcatId'])
                    ->whereIn('symptom_id', array_values($_GET['chk_name3']));
                if($_GET['orderby'] == "price"){
                    return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'data' => $data, 'shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'products' => getUnique($products->sortBy('price'))]);
                }elseif($_GET['orderby'] == "price-desc"){
                    return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'data' => $data, 'shapes' => $shapes,'categories' => $categories,  'brands' => $brands,  'products' => getUnique($products->sortByDesc('price'))]);
                }
                return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'products' => getUnique($products)]);
            }
        }else {

            return view('subcategory', ['symptoms' => $symptoms, 'subcategory' => $subcategory, 'parentCategory' => $parentCategory,'shapes' => $shapes,'data' => $data, 'categories' => $categories, 'brands' => $brands, 'products' => $products]);
        }




    }

    public function catalogFilter(Request $request)
    {
        $products = Product::all();
        $brands = Brand::all();
        $categories = DB::select('select * from categories');
        $shapes = Shape::all();

        if(isset($_GET['confirmBtn'])){
            //Выбран бренд, форма и сортировка по цене
            if((!empty($_GET['chk_name']) && !empty($_GET['chk_name2'])) && ($_GET['orderby'] == "price" || $_GET['orderby'] == "price-desc")){
                $products =  DB::table('products')
                    ->whereIn('brand_id', array_values($_GET['chk_name']))
                    ->whereIn('shape_id', array_values($_GET['chk_name2']))
                    ->get();
                if($_GET['orderby'] == "price"){
                    return view('catalog', ['shapes' => $shapes,'categories' => $categories,  'brands' => $brands,'dataShape' => $_GET['chk_name2'], 'dataBrand' => $_GET['chk_name'], 'products' => $products->sortBy('price')]);
                }elseif($_GET['orderby'] == "price-desc"){
                    return view('catalog', ['shapes' => $shapes,'categories' => $categories,  'brands' => $brands,'dataShape' => $_GET['chk_name2'], 'dataBrand' => $_GET['chk_name'], 'products' => $products->sortByDesc('price')]);
                }
                return view('catalog', ['shapes' => $shapes,'categories' => $categories,  'brands' => $brands,'dataShape' => $_GET['chk_name2'], 'dataBrand' => $_GET['chk_name'], 'products' => $products]);
                //Не выбран бренд и форма, выбрана сортировка по цене
            }elseif((empty($_GET['chk_name']) && empty($_GET['chk_name2'])) && ($_GET['orderby'] == "price" || $_GET['orderby'] == "price-desc")){
                    if($_GET['orderby'] == "price"){
                        return view('catalog', ['shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'products' => $products->sortBy('price')]);
                    }elseif($_GET['orderby'] == "price-desc"){
                        return view('catalog', ['shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'products' => $products->sortByDesc('price')]);
                    }
                    return view('catalog', ['shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'products' => $products]);
                //Не выбран бренд, выбрана форма и сортировка по цене
            }elseif((empty($_GET['chk_name']) && !empty($_GET['chk_name2'])) && ($_GET['orderby'] == "price" || $_GET['orderby'] == "price-desc")){
                $products =  DB::table('products')
                    ->whereIn('shape_id', array_values($_GET['chk_name2']))
                    ->get();
                if($_GET['orderby'] == "price"){
                    return view('catalog', ['shapes' => $shapes,'categories' => $categories,  'brands' => $brands,'dataShape' => $_GET['chk_name2'], 'products' => $products->sortBy('price')]);
                }elseif($_GET['orderby'] == "price-desc"){
                    return view('catalog', ['shapes' => $shapes,'categories' => $categories,  'brands' => $brands,'dataShape' => $_GET['chk_name2'], 'products' => $products->sortByDesc('price')]);
                }
                return view('catalog', ['shapes' => $shapes,'categories' => $categories,  'brands' => $brands,'dataShape' => $_GET['chk_name2'], 'products' => $products]);
                //Выбран бренд и сортировка по цене ,не выбрана форма
            }elseif((!empty($_GET['chk_name']) && empty($_GET['chk_name2'])) && ($_GET['orderby'] == "price" || $_GET['orderby'] == "price-desc")){
                $products =  DB::table('products')
                    ->whereIn('brand_id', array_values($_GET['chk_name']))
                    ->get();
                if($_GET['orderby'] == "price"){
                    return view('catalog', ['shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'dataBrand' => $_GET['chk_name'], 'products' => $products->sortBy('price')]);
                }elseif($_GET['orderby'] == "price-desc"){
                    return view('catalog', ['shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'dataBrand' => $_GET['chk_name'], 'products' => $products->sortByDesc('price')]);
                }
                return view('catalog', ['shapes' => $shapes,'categories' => $categories,  'brands' => $brands, 'dataBrand' => $_GET['chk_name'], 'products' => $products]);
                //Не выбран бренд и форма, выбрана сортировка по цене
            }
        }else {
            return view('catalog', ['shapes' => $shapes,'categories' => $categories, 'brands' => $brands, 'products' => $products]);
        }


    }
}
