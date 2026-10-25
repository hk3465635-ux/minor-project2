<?php
declare(strict_types=1);

/**
 * FD-Trend Authentic Product Catalog
 * 10 distinct products for each of the 11 categories (110 products total).
 */

$catalogProducts = [
    // =========================================================================
    // 1. T-SHIRTS (10 Products)
    // =========================================================================
    [
        'sku' => 'TSH-001',
        'name' => "Levi's Classic Batwing Heavyweight Crewneck T-Shirt",
        'brand' => "Levi's",
        'category_slug' => 't-shirts',
        'category_name' => 'T-Shirts',
        'subcategory' => 'Crewneck T-Shirts',
        'description' => "Crafted from 100% heavyweight 220 GSM combed cotton. Features the iconic batwing screen-print on the chest, a ribbed collar that retains its shape, and reinforced double-needle hem stitching. Pre-shrunk for an enduring fit through daily wear.",
        'price' => 1199.00,
        'mrp' => 1999.00,
        'stock' => 28,
        'image_url' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1527719327859-c6ce80353573?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'White, Black, Heather Grey',
        'material' => '100% Combed Cotton',
        'sizes' => 'S, M, L, XL, XXL',
        'rating' => 4.80,
        'review_count' => 124,
        'featured' => 1,
        'reviews' => [
            ['Aman Sharma', 5, 'Exceptional cotton weight and stitch quality. The collar does not sag even after multiple washes.'],
            ['Vikram Rao', 5, 'True to size with a comfortable modern fit. Best everyday white tee.']
        ]
    ],
    [
        'sku' => 'TSH-002',
        'name' => "Superdry Vintage Tokyo Graphic Streetwear Tee",
        'brand' => 'Superdry',
        'category_slug' => 't-shirts',
        'category_name' => 'T-Shirts',
        'subcategory' => 'Graphic Tees',
        'description' => "Distressed Japanese kanji and typography print across the front. Washed soft-touch single jersey cotton for an authentic lived-in vintage feel. Features signature orange sleeve tab and taped shoulder seams for lasting durability.",
        'price' => 1499.00,
        'mrp' => 2499.00,
        'stock' => 19,
        'image_url' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1503342394128-c104d54dba01?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Navy Blue, Washed Black, Olive Green',
        'material' => '100% Soft-Spun Cotton',
        'sizes' => 'S, M, L, XL',
        'rating' => 4.70,
        'review_count' => 88,
        'featured' => 1,
        'reviews' => [
            ['Rohan Mehta', 5, 'Super soft graphic tee with great print sharpness. The vintage wash looks premium.']
        ]
    ],
    [
        'sku' => 'TSH-003',
        'name' => "Zara Boxy Drop-Shoulder Oversized Tee",
        'brand' => 'Zara Man',
        'category_slug' => 't-shirts',
        'category_name' => 'T-Shirts',
        'subcategory' => 'Oversized Tees',
        'description' => "Contemporary oversized silhouette with dropped shoulder seams and relaxed wide sleeves. Crafted from dense 240 GSM organic cotton knit with a structured drape. Designed for minimal modern streetwear outfits.",
        'price' => 1290.00,
        'mrp' => 1890.00,
        'stock' => 24,
        'image_url' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Jet Black, Chalk White, Slate Grey',
        'material' => '100% Organic Dense Cotton',
        'sizes' => 'XS, S, M, L, XL',
        'rating' => 4.65,
        'review_count' => 62,
        'featured' => 0,
        'reviews' => [
            ['Karan Verma', 5, 'Perfect boxy cut. Pairs beautifully with wide denim and cargo pants.']
        ]
    ],
    [
        'sku' => 'TSH-004',
        'name' => "Nike Sportswear Club Essential Crewneck",
        'brand' => 'Nike',
        'category_slug' => 't-shirts',
        'category_name' => 'T-Shirts',
        'subcategory' => 'Solid Basics',
        'description' => "Everyday athletic staple cut from smooth lightweight cotton. Embroidered Futura swoosh logo at the chest, standard relaxed fit, and reinforced ribbed crew neckline that holds its profile.",
        'price' => 1395.00,
        'mrp' => 1795.00,
        'stock' => 35,
        'image_url' => 'https://images.unsplash.com/photo-1527719327859-c6ce80353573?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1562157873-818bc0726f68?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Black, White, Gym Red',
        'material' => '100% Cotton Jersey',
        'sizes' => 'S, M, L, XL, XXL',
        'rating' => 4.85,
        'review_count' => 210,
        'featured' => 1,
        'reviews' => [
            ['Siddharth Joshi', 5, 'Original Nike tee, authentic fabric quality and nice clean fit.']
        ]
    ],
    [
        'sku' => 'TSH-005',
        'name' => "AllSaints Underground Acid-Washed Heavyweight Tee",
        'brand' => 'AllSaints',
        'category_slug' => 't-shirts',
        'category_name' => 'T-Shirts',
        'subcategory' => 'Graphic Tees',
        'description' => "Individually garment-dyed and acid washed for unique tonal variation across each piece. Features distressed rock-inspired back graphic, raw-edge sleeve cuffs, and a relaxed drape.",
        'price' => 2490.00,
        'mrp' => 3990.00,
        'stock' => 14,
        'image_url' => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1576566588028-4147f3842f27?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Acid Washed Charcoal, Vintage Slate',
        'material' => '100% Mercerized Cotton',
        'sizes' => 'S, M, L, XL',
        'rating' => 4.90,
        'review_count' => 45,
        'featured' => 0,
        'reviews' => [
            ['Devendra Singh', 5, 'The acid wash looks stunning in person. Unmatched luxury streetwear tee.']
        ]
    ],
    [
        'sku' => 'TSH-006',
        'name' => "Tommy Hilfiger Nautical Yarn-Dyed Striped Tee",
        'brand' => 'Tommy Hilfiger',
        'category_slug' => 't-shirts',
        'category_name' => 'T-Shirts',
        'subcategory' => 'Crewneck T-Shirts',
        'description' => "Classic Breton-inspired horizontal striped pattern woven from fine yarn-dyed combed cotton. Embroidered mini flag logo on the left chest, taped neck seam, and breathable lightweight feel.",
        'price' => 1899.00,
        'mrp' => 2999.00,
        'stock' => 20,
        'image_url' => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1581655353564-df123a1eb820?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Navy / White, Red / White',
        'material' => '100% Premium Cotton',
        'sizes' => 'M, L, XL',
        'rating' => 4.75,
        'review_count' => 74,
        'featured' => 0,
        'reviews' => [
            ['Arjun Nair', 5, 'Clean maritime stripes and great cotton softness. Fits true to size.']
        ]
    ],
    [
        'sku' => 'TSH-007',
        'name' => "Carhartt WIP Pocket Workwear Heavyweight Tee",
        'brand' => 'Carhartt WIP',
        'category_slug' => 't-shirts',
        'category_name' => 'T-Shirts',
        'subcategory' => 'Solid Basics',
        'description' => "Legendary workwear toughness built with 230 GSM dense jersey. Includes single chest patch pocket with square woven Carhartt logo patch. Loose relaxed fit with side-seam construction to prevent twisting.",
        'price' => 1990.00,
        'mrp' => 2890.00,
        'stock' => 16,
        'image_url' => 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1562157873-818bc0726f68?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Hamilton Brown, Dark Navy, White',
        'material' => '100% Heavy Duty Cotton',
        'sizes' => 'S, M, L, XL, XXL',
        'rating' => 4.88,
        'review_count' => 93,
        'featured' => 0,
        'reviews' => [
            ['Gautam Sen', 5, 'Bulletproof tee. Heavyweight fabric that gets softer with every wash.']
        ]
    ],
    [
        'sku' => 'TSH-008',
        'name' => "Under Armour HeatGear Slim Athletic Training Tee",
        'brand' => 'Under Armour',
        'category_slug' => 't-shirts',
        'category_name' => 'T-Shirts',
        'subcategory' => 'Solid Basics',
        'description' => "Engineered for high performance with 4-way stretch HeatGear moisture-wicking technology. Anti-odor fabric treatment keeps you fresh during rigorous training sessions. Ergonomic flatlock seams reduce chafing.",
        'price' => 1499.00,
        'mrp' => 2199.00,
        'stock' => 22,
        'image_url' => 'https://images.unsplash.com/photo-1562157873-818bc0726f68?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1503342394128-c104d54dba01?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Pitch Grey, Royal Blue, Jet Black',
        'material' => '90% Polyester, 10% Elastane',
        'sizes' => 'M, L, XL, XXL',
        'rating' => 4.82,
        'review_count' => 110,
        'featured' => 0,
        'reviews' => [
            ['Pankaj Verma', 5, 'Wicks sweat instantly. Great athletic fit around shoulders and chest.']
        ]
    ],
    [
        'sku' => 'TSH-009',
        'name' => "H&M Relaxed Fit Streetwear Typography Tee",
        'brand' => 'H&M Studio',
        'category_slug' => 't-shirts',
        'category_name' => 'T-Shirts',
        'subcategory' => 'Graphic Tees',
        'description' => "Urban streetwear aesthetic with high-density architectural typography screen-printed across chest and back. Relaxed fit with dropped shoulders and ribbed neckline in breathable cotton jersey.",
        'price' => 899.00,
        'mrp' => 1499.00,
        'stock' => 30,
        'image_url' => 'https://images.unsplash.com/photo-1503342394128-c104d54dba01?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1622445268045-81676d1e44f8?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Off White, Mineral Grey',
        'material' => '100% Breathable Cotton',
        'sizes' => 'S, M, L, XL',
        'rating' => 4.60,
        'review_count' => 52,
        'featured' => 0,
        'reviews' => [
            ['Sameer Khan', 4, 'Great value for money and stylish graphic. Runs slightly loose as intended.']
        ]
    ],
    [
        'sku' => 'TSH-010',
        'name' => "Patagonia Organic Earth Hemp Crewneck Tee (Limited)",
        'brand' => 'Patagonia',
        'category_slug' => 't-shirts',
        'category_name' => 'T-Shirts',
        'subcategory' => 'Oversized Tees',
        'description' => "Eco-conscious blend of 55% true hemp and 45% organic cotton. Naturally breathable, highly durable, and cool to the skin. Fair Trade Certified sewn with plant-based dye finish.",
        'price' => 2699.00,
        'mrp' => 3499.00,
        'stock' => 0, // OUT OF STOCK TEST PRODUCT FOR T-SHIRTS
        'image_url' => 'https://images.unsplash.com/photo-1622445268045-81676d1e44f8?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Moss Green, Earth Tan',
        'material' => '55% Hemp, 45% Organic Cotton',
        'sizes' => 'S, M, L, XL',
        'rating' => 4.95,
        'review_count' => 67,
        'featured' => 0,
        'reviews' => [
            ['Nikhil Kapoor', 5, 'Unbelievable hand feel. Worth every rupee. Waiting for restock!']
        ]
    ],

    // =========================================================================
    // 2. SHIRTS (10 Products)
    // =========================================================================
    [
        'sku' => 'SHR-001',
        'name' => "Ralph Lauren Classic Fit Cotton Oxford Shirt",
        'brand' => 'Polo Ralph Lauren',
        'category_slug' => 'shirts',
        'category_name' => 'Shirts',
        'subcategory' => 'Casual Shirts',
        'description' => "Heritage button-down collar Oxford shirt woven from thick long-staple cotton yarn. Multi-colored signature pony embroidered on the left chest. Split back yoke with box pleat ensures ease of movement.",
        'price' => 3499.00,
        'mrp' => 5499.00,
        'stock' => 21,
        'image_url' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1620012253295-c15c429fccf8?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Oxford Sky Blue, Optical White, Light Pink',
        'material' => '100% Long-Staple Cotton',
        'sizes' => '38, 40, 42, 44, 46',
        'rating' => 4.90,
        'review_count' => 156,
        'featured' => 1,
        'reviews' => [
            ['Aditya Singhania', 5, 'The gold standard of Oxford shirts. Thick, durable fabric that gets better with time.'],
            ['Manish Goel', 5, 'Superb tailoring and comfortable classic fit. Pairs great with chinos and denim.']
        ]
    ],
    [
        'sku' => 'SHR-002',
        'name' => "Louis Philippe Pure French Linen Regular Fit Shirt",
        'brand' => 'Louis Philippe',
        'category_slug' => 'shirts',
        'category_name' => 'Shirts',
        'subcategory' => 'Linen Shirts',
        'description' => "Crafted from 100% pure Normandy flax linen. Incredibly breathable and thermoregulating for warm weather. Finished with mother-of-pearl buttons and a semi-spread cutaway collar.",
        'price' => 2899.00,
        'mrp' => 4299.00,
        'stock' => 18,
        'image_url' => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Sage Green, Beige Linen, White',
        'material' => '100% Normandy Pure Linen',
        'sizes' => '39, 40, 42, 44',
        'rating' => 4.80,
        'review_count' => 95,
        'featured' => 1,
        'reviews' => [
            ['Pranav Nair', 5, 'Extremely breathable in high heat. Feels luxurious on the skin.']
        ]
    ],
    [
        'sku' => 'SHR-003',
        'name' => "Peter England Buffalo Check Flannel Overshirt",
        'brand' => 'Peter England',
        'category_slug' => 'shirts',
        'category_name' => 'Shirts',
        'subcategory' => 'Check & Plaid Shirts',
        'description' => "Brushed heavy cotton flannel woven with a bold buffalo check pattern. Dual buttoned chest flap pockets and curved hemline. Ideal as a layering piece over plain t-shirts.",
        'price' => 1599.00,
        'mrp' => 2499.00,
        'stock' => 25,
        'image_url' => 'https://images.unsplash.com/photo-1603252109303-2751441dd157?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1607345366928-199ea26cfe3e?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Red / Black Check, Green / Navy Check',
        'material' => '100% Brushed Cotton Flannel',
        'sizes' => 'S, M, L, XL, XXL',
        'rating' => 4.70,
        'review_count' => 64,
        'featured' => 0,
        'reviews' => [
            ['Deepak Sharma', 5, 'Cozy warm flannel. Perfect for layering during evening outings.']
        ]
    ],
    [
        'sku' => 'SHR-004',
        'name' => "Van Heusen Tailored Non-Iron Luxury Dress Shirt",
        'brand' => 'Van Heusen',
        'category_slug' => 'shirts',
        'category_name' => 'Shirts',
        'subcategory' => 'Dress Shirts',
        'description' => "Crisp formal dress shirt engineered with 100% non-iron Supima cotton. Features taped seams to prevent puckering after washes, spread formal collar, and convertible French cuffs.",
        'price' => 2199.00,
        'mrp' => 3299.00,
        'stock' => 20,
        'image_url' => 'https://images.unsplash.com/photo-1620012253295-c15c429fccf8?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1598961942613-ba897716405b?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Crisp White, Ice Blue',
        'material' => '100% Non-Iron Supima Cotton',
        'sizes' => '39, 40, 42, 44',
        'rating' => 4.85,
        'review_count' => 130,
        'featured' => 1,
        'reviews' => [
            ['Rajeev Menon', 5, 'Genuinely wrinkle-free throughout long office meetings. Sharp cut.']
        ]
    ],
    [
        'sku' => 'SHR-005',
        'name' => "Zara Tropical Camp-Collar Resort Floral Shirt",
        'brand' => 'Zara Man',
        'category_slug' => 'shirts',
        'category_name' => 'Shirts',
        'subcategory' => 'Casual Shirts',
        'description' => "Silky viscose weave with botanical tropical foliage print. Features an open Cuban camp collar, relaxed dropped shoulders, and a straight boxy hemline with side slits.",
        'price' => 1790.00,
        'mrp' => 2590.00,
        'stock' => 16,
        'image_url' => 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1589310243389-96a5483213a8?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Navy Floral, Olive Palm',
        'material' => '100% Eco-Vero Viscose',
        'sizes' => 'S, M, L, XL',
        'rating' => 4.75,
        'review_count' => 58,
        'featured' => 0,
        'reviews' => [
            ['Ankit Bose', 5, 'Vibrant print and silky flow. The best holiday beach shirt.']
        ]
    ],
    [
        'sku' => 'SHR-006',
        'name' => "Wrangler Western Denim Snap-Button Workshirt",
        'brand' => 'Wrangler',
        'category_slug' => 'shirts',
        'category_name' => 'Shirts',
        'subcategory' => 'Casual Shirts',
        'description' => "Authentic 7.5 oz rinsed indigo denim shirt. Traditional pointed western shoulder yokes, pearl snap fasteners, and dual flap chest pockets with iconic 'W' embroidery.",
        'price' => 2299.00,
        'mrp' => 3499.00,
        'stock' => 22,
        'image_url' => 'https://images.unsplash.com/photo-1589310243389-96a5483213a8?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Medium Stonewash Indigo, Raw Denim',
        'material' => '100% Rigid Cotton Denim',
        'sizes' => 'M, L, XL, XXL',
        'rating' => 4.82,
        'review_count' => 79,
        'featured' => 0,
        'reviews' => [
            ['Sunny Deol', 5, 'Sturdy denim fabric with authentic pearl snaps. Classic rugged look.']
        ]
    ],
    [
        'sku' => 'SHR-007',
        'name' => "H&M Ribbed Fine-Wale Corduroy Overshirt",
        'brand' => 'H&M',
        'category_slug' => 'shirts',
        'category_name' => 'Shirts',
        'subcategory' => 'Casual Shirts',
        'description' => "Tactile 21-wale micro corduroy shirt tailored in a modern boxy cut. Features tortoiseshell button placket, button cuffs, and dual chest patch pockets. Softly brushed surface.",
        'price' => 1999.00,
        'mrp' => 2999.00,
        'stock' => 17,
        'image_url' => 'https://images.unsplash.com/photo-1607345366928-199ea26cfe3e?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1603252109303-2751441dd157?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Caramel Brown, Dark Forest Green',
        'material' => '100% Cotton Fine Corduroy',
        'sizes' => 'S, M, L, XL',
        'rating' => 4.68,
        'review_count' => 51,
        'featured' => 0,
        'reviews' => [
            ['Tushar Kulkarni', 5, 'Super stylish corduroy texture. Great for mild winter layering.']
        ]
    ],
    [
        'sku' => 'SHR-008',
        'name' => "FabIndia Khadi Cotton Mandarin Collar Kurta Shirt",
        'brand' => 'FabIndia',
        'category_slug' => 'shirts',
        'category_name' => 'Shirts',
        'subcategory' => 'Linen Shirts',
        'description' => "Handspun organic cotton khadi tailored with a sleek band collar and half-button placket. Breathable slub texture and regular comfort silhouette for modern ethnic dressing.",
        'price' => 1690.00,
        'mrp' => 2490.00,
        'stock' => 19,
        'image_url' => 'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Ivory White, Indigo Blue, Mustard',
        'material' => '100% Handloom Cotton Khadi',
        'sizes' => 'S, M, L, XL, XXL',
        'rating' => 4.79,
        'review_count' => 83,
        'featured' => 0,
        'reviews' => [
            ['Harshavardhan R', 5, 'Authentic khadi texture, keeps cool in summer and looks elegant.']
        ]
    ],
    [
        'sku' => 'SHR-009',
        'name' => "Tommy Hilfiger Poplin Banker Striped Smart Shirt",
        'brand' => 'Tommy Hilfiger',
        'category_slug' => 'shirts',
        'category_name' => 'Shirts',
        'subcategory' => 'Dress Shirts',
        'description' => "Smart vertical blue-and-white butcher stripe poplin cotton. Features button-down collar, embroidered flag logo on cuff, and back hanger loop. Tailored modern fit.",
        'price' => 2799.00,
        'mrp' => 4299.00,
        'stock' => 15,
        'image_url' => 'https://images.unsplash.com/photo-1604695573706-53170668f6a6?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1598961942613-ba897716405b?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Navy Stripe, Sky Blue Stripe',
        'material' => '100% Pure Compact Poplin Cotton',
        'sizes' => '39, 40, 42, 44',
        'rating' => 4.88,
        'review_count' => 67,
        'featured' => 0,
        'reviews' => [
            ['Kunal Chadha', 5, 'The stripes are crisp and high definition. Exceptional quality.']
        ]
    ],
    [
        'sku' => 'SHR-010',
        'name' => "Arrow Micro-Gingham Check Business Shirt (Sold Out)",
        'brand' => 'Arrow',
        'category_slug' => 'shirts',
        'category_name' => 'Shirts',
        'subcategory' => 'Check & Plaid Shirts',
        'description' => "Wrinkle-resistant twill fabric woven in a fine micro-gingham check. Standard semi-spread formal collar with removable collar stays, angled button cuffs, and reinforced side gussets.",
        'price' => 1899.00,
        'mrp' => 2999.00,
        'stock' => 0, // OUT OF STOCK TEST PRODUCT FOR SHIRTS
        'image_url' => 'https://images.unsplash.com/photo-1598961942613-ba897716405b?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Black / White Gingham, Blue Gingham',
        'material' => '100% Cotton Twill',
        'sizes' => '39, 40, 42, 44',
        'rating' => 4.70,
        'review_count' => 42,
        'featured' => 0,
        'reviews' => [
            ['Suresh Babu', 5, 'Very smart business check. Please restock size 40!']
        ]
    ],

    // =========================================================================
    // 3. JEANS (10 Products)
    // =========================================================================
    [
        'sku' => 'JEA-001',
        'name' => "Levi's 511 Slim Fit Stretch Dark Indigo Jeans",
        'brand' => "Levi's",
        'category_slug' => 'jeans',
        'category_name' => 'Jeans',
        'subcategory' => 'Slim Fit Jeans',
        'description' => "The iconic 511 slim cut: modern slim through the hip and thigh with a tailored narrow leg opening. Formulated with 99% organic cotton and 1% elastane for comfortable all-day flexibility without bagging out.",
        'price' => 2699.00,
        'mrp' => 3999.00,
        'stock' => 24,
        'image_url' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1582552938357-32b906df40cb?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Dark Indigo Wash, Jet Black',
        'material' => '99% Organic Cotton, 1% Elastane',
        'sizes' => '30, 32, 34, 36, 38',
        'rating' => 4.90,
        'review_count' => 340,
        'featured' => 1,
        'reviews' => [
            ['Yashraj Patel', 5, 'Absolute perfection in slim fit denim. The stretch makes sitting for hours easy.'],
            ['Abhinav Shukla', 5, 'Authentic Levi’s red tab quality. Deep indigo dye looks rich.']
        ]
    ],
    [
        'sku' => 'JEA-002',
        'name' => "Nudie Jeans Lean Dean Japanese Raw Selvedge Denim",
        'brand' => 'Nudie Jeans',
        'category_slug' => 'jeans',
        'category_name' => 'Jeans',
        'subcategory' => 'Straight Fit Jeans',
        'description' => "13.5 oz Japanese red-line selvedge denim woven on vintage shuttle looms. Unwashed dry denim designed to mold uniquely to your body, developing personalized honeycombs and whiskers.",
        'price' => 4999.00,
        'mrp' => 7999.00,
        'stock' => 12,
        'image_url' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1584370848010-d7fe6bc767ec?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Dry Raw Indigo Selvedge',
        'material' => '100% Japanese Shuttle-Loom Cotton',
        'sizes' => '30, 32, 34, 36',
        'rating' => 4.95,
        'review_count' => 77,
        'featured' => 1,
        'reviews' => [
            ['Dhruv Chawla', 5, 'True denimhead jeans. Incredible selvedge ID and crisp rigid structure.']
        ]
    ],
    [
        'sku' => 'JEA-003',
        'name' => "Calvin Klein Vintage Light Wash Relaxed Fit Denim",
        'brand' => 'Calvin Klein',
        'category_slug' => 'jeans',
        'category_name' => 'Jeans',
        'subcategory' => 'Relaxed Denim',
        'description' => "90s archival cut featuring a relaxed thigh and straight leg. Stone-washed for an authentic sun-faded ice blue hue with subtle natural fading at the thighs and seat. Branded leather jacron patch.",
        'price' => 3290.00,
        'mrp' => 4990.00,
        'stock' => 19,
        'image_url' => 'https://images.unsplash.com/photo-1582552938357-32b906df40cb?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1555689502-c4b22d76c56f?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Ice Blue Vintage Wash',
        'material' => '100% Cotton Denim',
        'sizes' => '28, 30, 32, 34, 36',
        'rating' => 4.75,
        'review_count' => 92,
        'featured' => 0,
        'reviews' => [
            ['Kartik Aaryan', 5, 'Unmatched 90s retro look. Comfortable wide silhouette that stacks over sneakers.']
        ]
    ],
    [
        'sku' => 'JEA-004',
        'name' => "Zara Tapered Fit Jet Black Resin-Coated Jeans",
        'brand' => 'Zara Man',
        'category_slug' => 'jeans',
        'category_name' => 'Jeans',
        'subcategory' => 'Tapered Jeans',
        'description' => "Stay-black dye technology coated with a subtle matte resin finish. Roomier thigh that tapers sharply down to the ankle for a sleek rocker silhouette. Custom black oxide rivets and hardware.",
        'price' => 2490.00,
        'mrp' => 3490.00,
        'stock' => 23,
        'image_url' => 'https://images.unsplash.com/photo-1584370848010-d7fe6bc767ec?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Stay Black Matte',
        'material' => '98% Cotton, 2% Spandex',
        'sizes' => '30, 32, 34, 36',
        'rating' => 4.70,
        'review_count' => 84,
        'featured' => 0,
        'reviews' => [
            ['Sumit Malhotra', 5, 'Does not fade even after 15 washes. Sharp taper looks great with boots.']
        ]
    ],
    [
        'sku' => 'JEA-005',
        'name' => "Diesel D-Strukt Distressed Ripped Street Denim",
        'brand' => 'Diesel',
        'category_slug' => 'jeans',
        'category_name' => 'Jeans',
        'subcategory' => 'Slim Fit Jeans',
        'description' => "Edgy hand-distressed detailing with internal backing patches across knees and thighs. Treated with enzyme wash and authentic abrasions. Slim fit with comfortable power stretch.",
        'price' => 4499.00,
        'mrp' => 7499.00,
        'stock' => 14,
        'image_url' => 'https://images.unsplash.com/photo-1604176354204-9268737828e4?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Distressed Medium Blue',
        'material' => '94% Cotton, 4% Polyester, 2% Elastane',
        'sizes' => '30, 32, 34, 36',
        'rating' => 4.88,
        'review_count' => 61,
        'featured' => 0,
        'reviews' => [
            ['Varun Oberoi', 5, 'Diesel distressing is in a league of its own. Premium denim feel.']
        ]
    ],
    [
        'sku' => 'JEA-006',
        'name' => "Wrangler Retro Bootcut Mid-Rise Heritage Jeans",
        'brand' => 'Wrangler',
        'category_slug' => 'jeans',
        'category_name' => 'Jeans',
        'subcategory' => 'Straight Fit Jeans',
        'description' => "Original western bootcut cut engineered to fit comfortably over cowboy and Chelsea boots. Traditional 5-pocket styling with copper rivets and heavy gauge contrast thread stitching.",
        'price' => 2199.00,
        'mrp' => 3199.00,
        'stock' => 20,
        'image_url' => 'https://images.unsplash.com/photo-1560243563-062bfc001d68?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Dark Tint Wash, Stonewash Blue',
        'material' => '100% Ring-Spun Cotton',
        'sizes' => '32, 34, 36, 38',
        'rating' => 4.65,
        'review_count' => 49,
        'featured' => 0,
        'reviews' => [
            ['Jagdish Prasad', 4, 'Heavy duty denim that sits perfectly over leather boots.']
        ]
    ],
    [
        'sku' => 'JEA-007',
        'name' => "Pull&Bear 90s Baggy Skater Denim Trousers",
        'brand' => 'Pull&Bear',
        'category_slug' => 'jeans',
        'category_name' => 'Jeans',
        'subcategory' => 'Relaxed Denim',
        'description' => "On-trend loose wide-leg fit inspired by 1990s skate culture. Sits lower on the hips with an extra roomy thigh and wide hem opening. Washed medium blue with soft broken-in twill.",
        'price' => 1999.00,
        'mrp' => 2899.00,
        'stock' => 17,
        'image_url' => 'https://images.unsplash.com/photo-1555689502-c4b22d76c56f?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1582552938357-32b906df40cb?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Mid Wash Vintage Indigo',
        'material' => '100% Cotton Denim',
        'sizes' => '28, 30, 32, 34',
        'rating' => 4.70,
        'review_count' => 55,
        'featured' => 0,
        'reviews' => [
            ['Ishan Roy', 5, 'The best baggy jeans right now. Perfect puddle hem over chunky sneakers.']
        ]
    ],
    [
        'sku' => 'JEA-008',
        'name' => "Pepe Jeans London Faded Smoke Grey Slim Fit Jeans",
        'brand' => 'Pepe Jeans',
        'category_slug' => 'jeans',
        'category_name' => 'Jeans',
        'subcategory' => 'Slim Fit Jeans',
        'description' => "Contemporary smoke grey wash with subtle whisker patterning on the front. Mid-rise waistband, slim straight leg, and premium stretch denim that maintains its shape after washing.",
        'price' => 2499.00,
        'mrp' => 3799.00,
        'stock' => 22,
        'image_url' => 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1584370848010-d7fe6bc767ec?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Smoke Grey Wash',
        'material' => '98% Cotton, 2% Elastane',
        'sizes' => '30, 32, 34, 36',
        'rating' => 4.80,
        'review_count' => 69,
        'featured' => 0,
        'reviews' => [
            ['Anand Mishra', 5, 'Very versatile grey shade. Pairs nicely with both black tees and white shirts.']
        ]
    ],
    [
        'sku' => 'JEA-009',
        'name' => "H&M High-Waisted Straight Ankle Crop Denim",
        'brand' => 'H&M Studio',
        'category_slug' => 'jeans',
        'category_name' => 'Jeans',
        'subcategory' => 'Straight Fit Jeans',
        'description' => "High waist silhouette featuring a straight cropped ankle finish. Crafted from rigid heavy cotton denim that flattens smoothly and holds its architectural shape.",
        'price' => 1799.00,
        'mrp' => 2499.00,
        'stock' => 26,
        'image_url' => 'https://images.unsplash.com/photo-1598554747436-c9293d6a588f?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1565084888279-aca607ecce0c?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Vintage Off-White, Light Blue',
        'material' => '100% Rigid Organic Cotton',
        'sizes' => '26, 28, 30, 32',
        'rating' => 4.68,
        'review_count' => 88,
        'featured' => 0,
        'reviews' => [
            ['Priya Sen', 5, 'Flattering high waist fit and clean ankle cut. Looks so chic with loafers.']
        ]
    ],
    [
        'sku' => 'JEA-010',
        'name' => "Lee Extreme Motion Custom Stretch Denim (Sold Out)",
        'brand' => 'Lee',
        'category_slug' => 'jeans',
        'category_name' => 'Jeans',
        'subcategory' => 'Tapered Jeans',
        'description' => "Features an active comfort waistband and multi-directional stretch fabric. Tapered leg with reinforced pockets and moisture-dispersing fabric weave.",
        'price' => 2299.00,
        'mrp' => 3299.00,
        'stock' => 0, // OUT OF STOCK TEST PRODUCT FOR JEANS
        'image_url' => 'https://images.unsplash.com/photo-1565084888279-aca607ecce0c?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Dark Wash Classic',
        'material' => '97% Cotton, 3% Spandex',
        'sizes' => '30, 32, 34, 36',
        'rating' => 4.75,
        'review_count' => 38,
        'featured' => 0,
        'reviews' => [
            ['Rahul Trivedi', 5, 'Super stretchy and comfortable for travel. Hope this comes back in stock.']
        ]
    ],

    // =========================================================================
    // 4. TROUSERS (10 Products)
    // =========================================================================
    [
        'sku' => 'TRS-001',
        'name' => "Dockers Signature Iron-Free Khaki Straight Trousers",
        'brand' => 'Dockers',
        'category_slug' => 'trousers',
        'category_name' => 'Trousers',
        'subcategory' => 'Chinos & Khakis',
        'description' => "The quintessential American khaki pant. Woven from wrinkle-free stretch twill with a flex comfort waistband. Flat front styling with side slash pockets and rear button-through welt pockets.",
        'price' => 2499.00,
        'mrp' => 3799.00,
        'stock' => 25,
        'image_url' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1506629082955-511b1aa562c8?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Classic Khaki Tan, British Navy, Charcoal',
        'material' => '98% Cotton, 2% Elastane',
        'sizes' => '30, 32, 34, 36, 38',
        'rating' => 4.88,
        'review_count' => 142,
        'featured' => 1,
        'reviews' => [
            ['Manoj Bajpayee', 5, 'Original Dockers quality. Stays pressed all day without wrinkles.'],
            ['Harish Chandra', 5, 'Perfect fit around the waist with comfortable stretch throughout.']
        ]
    ],
    [
        'sku' => 'TRS-002',
        'name' => "Raymond Fine Wool-Blend Double-Pleat Formal Trousers",
        'brand' => 'Raymond',
        'category_slug' => 'trousers',
        'category_name' => 'Trousers',
        'subcategory' => 'Formal Trousers',
        'description' => "Tailored from a premium Super 100s wool-blend fabric with immaculate double forward pleats. Features a French fly closure, curtain waistband, and half-lined interior for smooth drape over dress shoes.",
        'price' => 2999.00,
        'mrp' => 4499.00,
        'stock' => 18,
        'image_url' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1517445312882-bc9910d016b7?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Charcoal Grey, Midnight Blue, Black',
        'material' => '65% Fine Wool, 35% Polyester',
        'sizes' => '30, 32, 34, 36, 38',
        'rating' => 4.92,
        'review_count' => 88,
        'featured' => 1,
        'reviews' => [
            ['Vivek Oberoi', 5, 'Raymond tailoring at its best. Impeccable formal finish and drape.']
        ]
    ],
    [
        'sku' => 'TRS-003',
        'name' => "Carhartt WIP Regular Aviation Ripstop Cargo Pants",
        'brand' => 'Carhartt WIP',
        'category_slug' => 'trousers',
        'category_name' => 'Trousers',
        'subcategory' => 'Cargo Pants',
        'description' => "Heavy-duty 6.5 oz Columbia cotton ripstop trousers. Features double-layer reinforced knees, spacious side bellows cargo pockets with hidden snaps, and adjustable drawstrings at the ankle openings.",
        'price' => 3490.00,
        'mrp' => 4990.00,
        'stock' => 16,
        'image_url' => 'https://images.unsplash.com/photo-1506629082955-511b1aa562c8?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Military Olive Green, Desert Sand, Black',
        'material' => '100% Cotton Ripstop Weave',
        'sizes' => '30, 32, 34, 36',
        'rating' => 4.85,
        'review_count' => 97,
        'featured' => 0,
        'reviews' => [
            ['Kunal Kapoor', 5, 'Rugged ripstop fabric that can take heavy beatings. Outstanding cargo styling.']
        ]
    ],
    [
        'sku' => 'TRS-004',
        'name' => "Zara Relaxed Drawstring 100% Pure Linen Trousers",
        'brand' => 'Zara Man',
        'category_slug' => 'trousers',
        'category_name' => 'Trousers',
        'subcategory' => 'Linen Trousers',
        'description' => "Breezy pure linen trousers designed with an elasticated waistband and cotton herringbone drawstring. Relaxed straight leg, deep side pockets, and breathable airy weave ideal for seaside and summer getaways.",
        'price' => 2290.00,
        'mrp' => 3290.00,
        'stock' => 20,
        'image_url' => 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1509551388413-e18d0ac5d495?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Natural Ecru Linen, White, Sky Blue',
        'material' => '100% European Flax Linen',
        'sizes' => 'S, M, L, XL',
        'rating' => 4.78,
        'review_count' => 65,
        'featured' => 0,
        'reviews' => [
            ['Samir Soni', 5, 'Effortlessly stylish summer linen bottoms. Extremely comfortable waistband.']
        ]
    ],
    [
        'sku' => 'TRS-005',
        'name' => "Marks & Spencer Tailored Fit Charcoal Stretch Slacks",
        'brand' => 'Marks & Spencer',
        'category_slug' => 'trousers',
        'category_name' => 'Trousers',
        'subcategory' => 'Formal Trousers',
        'description' => "Modern flat-front office dress trousers equipped with 360-degree stretch technology. Active flex waistband hidden inside, blind hem finish, and stain-resistant fabric treatment.",
        'price' => 2799.00,
        'mrp' => 3999.00,
        'stock' => 22,
        'image_url' => 'https://images.unsplash.com/photo-1517445312882-bc9910d016b7?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1516826957135-700dedea698c?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Charcoal Melange, Navy, Black',
        'material' => '70% Polyester, 28% Viscose, 2% Elastane',
        'sizes' => '30, 32, 34, 36, 38',
        'rating' => 4.80,
        'review_count' => 112,
        'featured' => 1,
        'reviews' => [
            ['Naveen Jindal', 5, 'M&S formal slacks never disappoint. Fits impeccably with clean drape.']
        ]
    ],
    [
        'sku' => 'TRS-006',
        'name' => "Tommy Hilfiger Smart Casual Slim Fit Chino Trousers",
        'brand' => 'Tommy Hilfiger',
        'category_slug' => 'trousers',
        'category_name' => 'Trousers',
        'subcategory' => 'Chinos & Khakis',
        'description' => "Slim tailored chino trousers crafted in soft organic cotton stretch satin twill. Features signature tricolor ribbon loop on the coin pocket, buttoned back welt pockets, and zip fly with horn button closure.",
        'price' => 2999.00,
        'mrp' => 4599.00,
        'stock' => 19,
        'image_url' => 'https://images.unsplash.com/photo-1591195853828-11db59a44f6b?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Deep Navy, Stone Grey, Khaki',
        'material' => '97% Organic Cotton, 3% Elastane',
        'sizes' => '30, 32, 34, 36',
        'rating' => 4.84,
        'review_count' => 78,
        'featured' => 0,
        'reviews' => [
            ['Prateek Babbar', 5, 'Super soft satin twill finish. Looks very smart with loafers.']
        ]
    ],
    [
        'sku' => 'TRS-007',
        'name' => "COS Minimalist Wide-Leg Pleated Wool-Blend Trousers",
        'brand' => 'COS',
        'category_slug' => 'trousers',
        'category_name' => 'Trousers',
        'subcategory' => 'Formal Trousers',
        'description' => "Architectural Scandinavian tailoring with an exaggerated wide leg and sharp single front crease. High-rise waist with adjustable side tabs, clean concealed zip fly, and deep side welt pockets.",
        'price' => 3890.00,
        'mrp' => 5990.00,
        'stock' => 14,
        'image_url' => 'https://images.unsplash.com/photo-1509551388413-e18d0ac5d495?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Dark Taupe, Midnight Black',
        'material' => '54% Recycled Polyester, 44% Wool, 2% Elastane',
        'sizes' => '30, 32, 34, 36',
        'rating' => 4.90,
        'review_count' => 41,
        'featured' => 0,
        'reviews' => [
            ['Abhishek Ray', 5, 'Incredible designer silhouette. Flattering wide cut with flawless proportions.']
        ]
    ],
    [
        'sku' => 'TRS-008',
        'name' => "Uniqlo Vintage Regular Fit Chino Pants",
        'brand' => 'Uniqlo',
        'category_slug' => 'trousers',
        'category_name' => 'Trousers',
        'subcategory' => 'Chinos & Khakis',
        'description' => "Dense high-count cotton twill enzyme washed for an authentic vintage soft hand feel. Traditional straight fit through the leg, durable natural nut buttons, and coin pocket detail.",
        'price' => 1990.00,
        'mrp' => 2990.00,
        'stock' => 28,
        'image_url' => 'https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Beige Chino, Olive, Black',
        'material' => '100% Dense Cotton Twill',
        'sizes' => '29, 30, 32, 34, 36',
        'rating' => 4.77,
        'review_count' => 165,
        'featured' => 0,
        'reviews' => [
            ['Sanjay Roy', 5, 'Daily workhorse pants. High quality fabric that holds up to daily washing.']
        ]
    ],
    [
        'sku' => 'TRS-009',
        'name' => "Van Heusen Ankle-Length Smart Tech Stretch Trousers",
        'brand' => 'Van Heusen',
        'category_slug' => 'trousers',
        'category_name' => 'Trousers',
        'subcategory' => 'Formal Trousers',
        'description' => "Tapered ankle-crop formal trousers featuring moisture-wicking and quick-drying smart tech fabric. Clean flat front styling with an autofit expandable waistband.",
        'price' => 1899.00,
        'mrp' => 2799.00,
        'stock' => 21,
        'image_url' => 'https://images.unsplash.com/photo-1516826957135-700dedea698c?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1517445312882-bc9910d016b7?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Formal Black, Steel Grey',
        'material' => '78% Terry, 20% Rayon, 2% Spandex',
        'sizes' => '30, 32, 34, 36',
        'rating' => 4.72,
        'review_count' => 54,
        'featured' => 0,
        'reviews' => [
            ['Girish Kumar', 4, 'Very smart ankle length. Looks great paired with monk strap shoes.']
        ]
    ],
    [
        'sku' => 'TRS-010',
        'name' => "Under Armour Tactical Elite Utility Cargo (Sold Out)",
        'brand' => 'Under Armour',
        'category_slug' => 'trousers',
        'category_name' => 'Trousers',
        'subcategory' => 'Cargo Pants',
        'description' => "Storm technology water-resistant coated cargo trousers. Ultra-durable ripstop fabrication, reinforced knife clip pockets, and articulated knees for complete tactical mobility.",
        'price' => 2999.00,
        'mrp' => 4499.00,
        'stock' => 0, // OUT OF STOCK TEST PRODUCT FOR TROUSERS
        'image_url' => 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1506629082955-511b1aa562c8?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Black Ops, Marine Green',
        'material' => '100% Water-Resistant Ripstop',
        'sizes' => '32, 34, 36',
        'rating' => 4.90,
        'review_count' => 63,
        'featured' => 0,
        'reviews' => [
            ['Col. Amarjeet Singh', 5, 'Rugged construction and excellent pocket ergonomics. Restock soon please!']
        ]
    ],

    // =========================================================================
    // 5. JACKETS (10 Products - ONLY JACKETS, NO HOODIES)
    // =========================================================================
    [
        'sku' => 'JCK-001',
        'name' => "Zara Faux Leather Asymmetric Biker Moto Jacket",
        'brand' => 'Zara Man',
        'category_slug' => 'jackets',
        'category_name' => 'Jackets',
        'subcategory' => 'Leather Jackets',
        'description' => "Heavyweight grain-textured vegan faux leather jacket. Features classic asymmetric silver metal zip closure, notched snap-down lapels, zippered wrist gussets, and belt buckle hem accent. Fully lined with quilted diamond satin.",
        'price' => 3990.00,
        'mrp' => 6990.00,
        'stock' => 15,
        'image_url' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1544441893-675973e31985?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1576871337622-98d48d1cf531?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Motorcycle Black, Antique Brown',
        'material' => '100% Polyurethane Outer, 100% Polyester Satin Lining',
        'sizes' => 'S, M, L, XL',
        'rating' => 4.88,
        'review_count' => 180,
        'featured' => 1,
        'reviews' => [
            ['Kabir Sethi', 5, 'Looks and feels like genuine lambskin. Hardware is heavy and high grade.'],
            ['Nitin Gadkari', 5, 'Instant head-turner. Warm inside lining and razor-sharp silhouette.']
        ]
    ],
    [
        'sku' => 'JCK-002',
        'name' => "Levi's The Trucker Classic Heavy Denim Jacket",
        'brand' => "Levi's",
        'category_slug' => 'jackets',
        'category_name' => 'Jackets',
        'subcategory' => 'Denim Jackets',
        'description' => "The original denim jacket since 1967. Sturdy 14 oz non-stretch cotton denim with button-flap chest pockets, welt hand pockets, and adjustable waist tabs. Cut with a regular straight fit that sits right at the hips.",
        'price' => 3499.00,
        'mrp' => 5499.00,
        'stock' => 22,
        'image_url' => 'https://images.unsplash.com/photo-1576871337622-98d48d1cf531?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1543076447-215ad9ba6923?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Rigid Dark Stonewash, Medium Blue Wash',
        'material' => '100% Cotton Denim',
        'sizes' => 'S, M, L, XL, XXL',
        'rating' => 4.95,
        'review_count' => 220,
        'featured' => 1,
        'reviews' => [
            ['Dev Patel', 5, 'A wardrobe essential that lasts a decade. Genuine Levi’s trucker jacket.']
        ]
    ],
    [
        'sku' => 'JCK-003',
        'name' => "Alpha Industries MA-1 Flight Reversible Bomber Jacket",
        'brand' => 'Alpha Industries',
        'category_slug' => 'jackets',
        'category_name' => 'Jackets',
        'subcategory' => 'Bomber Jackets',
        'description' => "Genuine military flight nylon bomber jacket. Reversible emergency Indian orange lining, signature red 'Remove Before Flight' utility ribbon tag on left sleeve, knit ribbed collar, cuffs, and waistband.",
        'price' => 4999.00,
        'mrp' => 8499.00,
        'stock' => 12,
        'image_url' => 'https://images.unsplash.com/photo-1544441893-675973e31985?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1548883354-7622d03aca27?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Sage Green, Flight Black',
        'material' => '100% Mid-Weight Flight Nylon',
        'sizes' => 'M, L, XL',
        'rating' => 4.92,
        'review_count' => 115,
        'featured' => 1,
        'reviews' => [
            ['Varun Dhawan', 5, 'The real deal MA-1 bomber. Reversible orange interior is iconic.']
        ]
    ],
    [
        'sku' => 'JCK-004',
        'name' => "The North Face Nuptse 700-Fill Down Puffer Jacket",
        'brand' => 'The North Face',
        'category_slug' => 'jackets',
        'category_name' => 'Jackets',
        'subcategory' => 'Puffer Jackets',
        'description' => "Heritage 1996 retro boxy silhouette packed with RDS-certified 700-fill goose down insulation. Ripstop shell with DWR water-repellent finish, packable hood stows inside collar, and bungee cinch hem.",
        'price' => 5999.00,
        'mrp' => 9999.00,
        'stock' => 10,
        'image_url' => 'https://images.unsplash.com/photo-1548883354-7622d03aca27?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1520975954732-35dd22299614?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'TNF Black, Summit Gold / Black',
        'material' => '100% Recycled Ripstop Nylon, 700 Fill Goose Down',
        'sizes' => 'S, M, L, XL',
        'rating' => 4.98,
        'review_count' => 240,
        'featured' => 1,
        'reviews' => [
            ['Ranveer Singh', 5, 'Super warm yet remarkably lightweight. Nothing beats the Nuptse puffer.']
        ]
    ],
    [
        'sku' => 'JCK-005',
        'name' => "Nike Windrunner Hooded Water-Repellent Running Jacket",
        'brand' => 'Nike',
        'category_slug' => 'jackets',
        'category_name' => 'Jackets',
        'subcategory' => 'Windbreakers',
        'description' => "Iconic 26-degree chevron design line windbreaker. Made with lightweight woven fabric that resists wind and light rain. Vent at the back increases airflow, with zippered side hand pockets.",
        'price' => 2995.00,
        'mrp' => 4495.00,
        'stock' => 18,
        'image_url' => 'https://images.unsplash.com/photo-1520975954732-35dd22299614?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1544022613-e87ca75a784a?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Black / White Colorblock, Navy Blue',
        'material' => '100% Recycled Polyester Taffeta',
        'sizes' => 'S, M, L, XL',
        'rating' => 4.80,
        'review_count' => 95,
        'featured' => 0,
        'reviews' => [
            ['Ajay Devgn', 5, 'Great for morning runs and casual daily wear. Shields wind effectively.']
        ]
    ],
    [
        'sku' => 'JCK-006',
        'name' => "Carhartt WIP Michigan Duck Canvas Chore Coat Jacket",
        'brand' => 'Carhartt WIP',
        'category_slug' => 'jackets',
        'category_name' => 'Jackets',
        'subcategory' => 'Bomber Jackets',
        'description' => "Rugged 12 oz organic Dearborn cotton duck canvas chore jacket. Contrast fine-wale corduroy collar, four front utility patch pockets, personalized metal tack buttons, and triple-stitched stress seams.",
        'price' => 4290.00,
        'mrp' => 6490.00,
        'stock' => 14,
        'image_url' => 'https://images.unsplash.com/photo-1543076447-215ad9ba6923?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1576871337622-98d48d1cf531?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Carhartt Brown, Black Duck',
        'material' => '100% Organic Dearborn Canvas',
        'sizes' => 'M, L, XL',
        'rating' => 4.90,
        'review_count' => 82,
        'featured' => 0,
        'reviews' => [
            ['Gaurav Kapur', 5, 'Heavy canvas chore coat that will last a lifetime. Authentic workwear.']
        ]
    ],
    [
        'sku' => 'JCK-007',
        'name' => "Baracuta G9 Original Suede Harrington Jacket",
        'brand' => 'Baracuta',
        'category_slug' => 'jackets',
        'category_name' => 'Jackets',
        'subcategory' => 'Bomber Jackets',
        'description' => "The original British Harrington jacket in velvety soft goat suede. Stand collar with dual button closure, Fraser Tartan lining, raglan sleeves, ribbed waistband, and umbrella back vent.",
        'price' => 4899.00,
        'mrp' => 7999.00,
        'stock' => 11,
        'image_url' => 'https://images.unsplash.com/photo-1544022613-e87ca75a784a?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Tobacco Brown Suede, Navy Suede',
        'material' => '100% Genuine Goat Suede Leather',
        'sizes' => '38, 40, 42, 44',
        'rating' => 4.94,
        'review_count' => 52,
        'featured' => 0,
        'reviews' => [
            ['Siddharth Mallya', 5, 'The suede is butter-soft. Classic gentleman’s jacket.']
        ]
    ],
    [
        'sku' => 'JCK-008',
        'name' => "Hugo Boss Unstructured Slim Fit Linen-Wool Blazer Jacket",
        'brand' => 'Hugo Boss',
        'category_slug' => 'jackets',
        'category_name' => 'Jackets',
        'subcategory' => 'Bomber Jackets',
        'description' => "Deconstructed single-breasted jacket tailored from an Italian linen-wool blend. Unlined construction for natural shoulder drape, notch lapels, patch front pockets, and double back vents.",
        'price' => 4999.00,
        'mrp' => 8999.00,
        'stock' => 13,
        'image_url' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1516257984-b1b4d707412e?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Navy Melange, Sand Beige',
        'material' => '55% Linen, 45% Virgin Wool',
        'sizes' => '38, 40, 42, 44',
        'rating' => 4.91,
        'review_count' => 73,
        'featured' => 0,
        'reviews' => [
            ['Farhan Akhtar', 5, 'Super lightweight and elegant. Elevates any smart-casual look.']
        ]
    ],
    [
        'sku' => 'JCK-009',
        'name' => "Levi's Sherpa-Lined Corduroy Winter Trucker Jacket",
        'brand' => "Levi's",
        'category_slug' => 'jackets',
        'category_name' => 'Jackets',
        'subcategory' => 'Denim Jackets',
        'description' => "Warm plush faux-sherpa fleece lining throughout the interior body and collar. Thick-wale corduroy outer shell, quilted sleeve lining, and Levi's metal snap-button closure.",
        'price' => 3899.00,
        'mrp' => 5999.00,
        'stock' => 16,
        'image_url' => 'https://images.unsplash.com/photo-1516257984-b1b4d707412e?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Warm Caramel Cord, Black Cord',
        'material' => '100% Cotton Corduroy, Sherpa Fleece Lining',
        'sizes' => 'M, L, XL',
        'rating' => 4.87,
        'review_count' => 104,
        'featured' => 0,
        'reviews' => [
            ['Bobby Deol', 5, 'Very cozy in chilly weather. Fits true to size with room for a sweater.']
        ]
    ],
    [
        'sku' => 'JCK-010',
        'name' => "Burberry Heritage Double-Breasted Trench Coat Jacket (Sold Out)",
        'brand' => 'Burberry',
        'category_slug' => 'jackets',
        'category_name' => 'Jackets',
        'subcategory' => 'Windbreakers',
        'description' => "The quintessential weatherproof cotton gabardine trench coat. Features storm flap, buffalo horn buttons, D-ring leather buckle belt, and vintage house check lining.",
        'price' => 8999.00,
        'mrp' => 14999.00,
        'stock' => 0, // OUT OF STOCK TEST PRODUCT FOR JACKETS
        'image_url' => 'https://images.unsplash.com/photo-1548883354-9540c4961168?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1544441893-675973e31985?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Honey Beige, Classic Black',
        'material' => '100% Weatherproof Cotton Gabardine',
        'sizes' => '38, 40, 42',
        'rating' => 4.99,
        'review_count' => 65,
        'featured' => 0,
        'reviews' => [
            ['Lord Meghnad', 5, 'Masterpiece of outerwear. Waiting eagerly for restock in Honey Beige.']
        ]
    ],

    // =========================================================================
    // 6. HOODIES (10 Products - ONLY HOODIES, NO JACKETS)
    // =========================================================================
    [
        'sku' => 'HUD-001',
        'name' => "Champion Reverse Weave Heavyweight Pullover Hoodie",
        'brand' => 'Champion',
        'category_slug' => 'hoodies',
        'category_name' => 'Hoodies',
        'subcategory' => 'Pullover Hoodies',
        'description' => "The original athletic sweatshirt patented in 1938. Cut on the cross-grain (Reverse Weave) to resist vertical shrinkage. Dense 430 GSM fleece, signature ribbed side stretch panels, double-layer hood, and embroidered 'C' logo on chest and wrist.",
        'price' => 2499.00,
        'mrp' => 3999.00,
        'stock' => 24,
        'image_url' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1509967419530-da38b4704bc6?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Silver Grey, Oxford Navy, Deep Black',
        'material' => '82% Heavyweight Cotton, 18% Polyester Fleece',
        'sizes' => 'S, M, L, XL, XXL',
        'rating' => 4.94,
        'review_count' => 280,
        'featured' => 1,
        'reviews' => [
            ['Rishi Kapoor', 5, 'The heaviest, highest quality hoodie on the market. Reverse weave stops any shrinking.'],
            ['Tariq Ali', 5, 'Substantial hood that stays upright. Best hoodie I have ever owned.']
        ]
    ],
    [
        'sku' => 'HUD-002',
        'name' => "Nike Club French Terry Minimalist Fleece Pullover",
        'brand' => 'Nike',
        'category_slug' => 'hoodies',
        'category_name' => 'Hoodies',
        'subcategory' => 'Fleece Hoodies',
        'description' => "Soft brushed-back French terry fleece engineered for cozy daily wear. Kangaroo hand pocket, drawcord hood with metallic grommets, ribbed cuffs and hem, and clean embroidered white Futura swoosh on the chest.",
        'price' => 2295.00,
        'mrp' => 3295.00,
        'stock' => 26,
        'image_url' => 'https://images.unsplash.com/photo-1509967419530-da38b4704bc6?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Black, Heather Grey, Midnight Navy',
        'material' => '80% Cotton, 20% Polyester Brushed Terry',
        'sizes' => 'S, M, L, XL, XXL',
        'rating' => 4.85,
        'review_count' => 195,
        'featured' => 1,
        'reviews' => [
            ['Kunal Mehra', 5, 'Super comfortable fleece. Fleece inside is ultra plush.']
        ]
    ],
    [
        'sku' => 'HUD-003',
        'name' => "Adidas Originals Trefoil Full-Zip Track Hoodie",
        'brand' => 'Adidas Originals',
        'category_slug' => 'hoodies',
        'category_name' => 'Hoodies',
        'subcategory' => 'Zip-Up Hoodies',
        'description' => "Classic streetwear full-zip hoodie with the archival 3-Stripes down both sleeves and iconic Trefoil logo on the chest. Full metal front zipper, split kangaroo pockets, and soft cotton-blend fleece.",
        'price' => 2799.00,
        'mrp' => 4299.00,
        'stock' => 19,
        'image_url' => 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Core Black / White, Collegiate Navy',
        'material' => '70% Cotton, 30% Recycled Polyester Fleece',
        'sizes' => 'S, M, L, XL',
        'rating' => 4.80,
        'review_count' => 135,
        'featured' => 1,
        'reviews' => [
            ['Shankar Mahadevan', 5, 'Classic zip hoodie. Great zipper action and timeless 3-stripes style.']
        ]
    ],
    [
        'sku' => 'HUD-004',
        'name' => "Fear of God Essentials Boxy Drop-Shoulder Oversized Hoodie",
        'brand' => 'Essentials',
        'category_slug' => 'hoodies',
        'category_name' => 'Hoodies',
        'subcategory' => 'Oversized Hoodies',
        'description' => "The defining silhouette in contemporary streetwear: roomy dropped shoulders, generous chest volume, and high-density rubberized 3D logo across the chest. Built from dense 400 GSM cotton-poly fleece with no drawstrings for a clean architectural neck.",
        'price' => 3890.00,
        'mrp' => 5990.00,
        'stock' => 15,
        'image_url' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1618354691792-d1d42acfd860?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Oatmeal Heather, Dark Heather Oatmeal, Black',
        'material' => '80% Heavy Cotton, 20% Polyester',
        'sizes' => 'S, M, L, XL',
        'rating' => 4.96,
        'review_count' => 165,
        'featured' => 1,
        'reviews' => [
            ['Aayush Sharma', 5, 'The oversized drape is unmatched. Thickest hood shape out there.']
        ]
    ],
    [
        'sku' => 'HUD-005',
        'name' => "Zara Pastel Mauve Washed Minimalist Pullover Hoodie",
        'brand' => 'Zara Man',
        'category_slug' => 'hoodies',
        'category_name' => 'Hoodies',
        'subcategory' => 'Pullover Hoodies',
        'description' => "Garment-dyed pastel mauve tone on pure organic loopback cotton. Tonal ribbed trims, relaxed raglan sleeve cut, and clean seamless pocket styling for an understated aesthetic.",
        'price' => 1990.00,
        'mrp' => 2990.00,
        'stock' => 21,
        'image_url' => 'https://images.unsplash.com/photo-1618354691792-d1d42acfd860?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Pastel Mauve, Washed Mint, Sage',
        'material' => '100% Organic Loopback Cotton',
        'sizes' => 'S, M, L, XL',
        'rating' => 4.72,
        'review_count' => 68,
        'featured' => 0,
        'reviews' => [
            ['Neil Nitin', 5, 'Beautiful washed pastel shade. Soft loopback interior feels great without fleece fuzz.']
        ]
    ],
    [
        'sku' => 'HUD-006',
        'name' => "Superdry Vintage Athletic College Arch-Logo Hoodie",
        'brand' => 'Superdry',
        'category_slug' => 'hoodies',
        'category_name' => 'Hoodies',
        'subcategory' => 'Pullover Hoodies',
        'description' => "Vintage collegiate Americana styling with multi-layer felt applique chest branding. Heavy brushed lining, thick flat braided drawstrings, and signature orange bar-tack reinforcements.",
        'price' => 2699.00,
        'mrp' => 4499.00,
        'stock' => 18,
        'image_url' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Athletic Navy, Marled Grey, Burgundy',
        'material' => '75% Cotton, 25% Polyester Warm Fleece',
        'sizes' => 'M, L, XL, XXL',
        'rating' => 4.88,
        'review_count' => 94,
        'featured' => 0,
        'reviews' => [
            ['Abhay Deol', 5, 'Superdry felt lettering is top notch. Thick warm fleece for chilly nights.']
        ]
    ],
    [
        'sku' => 'HUD-007',
        'name' => "Puma Studio All-Black Performance Loopback Hoodie",
        'brand' => 'Puma',
        'category_slug' => 'hoodies',
        'category_name' => 'Hoodies',
        'subcategory' => 'Fleece Hoodies',
        'description' => "Sleek all-black workout and streetwear hybrid. Formulated with Puma dryCELL moisture-management yarns and ergonomic cutlines for unrestricted mobility during movement.",
        'price' => 1999.00,
        'mrp' => 3299.00,
        'stock' => 22,
        'image_url' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1578632767115-351597cf2477?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Puma Black, Castlerock Grey',
        'material' => '68% Cotton, 32% Polyester dryCELL',
        'sizes' => 'S, M, L, XL',
        'rating' => 4.79,
        'review_count' => 81,
        'featured' => 0,
        'reviews' => [
            ['Girish Karnad', 4, 'Great athletic fit, breathable and does not overheat when active.']
        ]
    ],
    [
        'sku' => 'HUD-008',
        'name' => "H&M Relaxed Fit Raw-Hem Cropped Fleece Hoodie",
        'brand' => 'H&M',
        'category_slug' => 'hoodies',
        'category_name' => 'Hoodies',
        'subcategory' => 'Oversized Hoodies',
        'description' => "Cropped silhouette with raw-edge unfinished bottom hem. Wide dropped sleeves, double-layer drawstring hood, and ultra-soft brushed fleece interior designed for layered styling.",
        'price' => 1499.00,
        'mrp' => 2299.00,
        'stock' => 25,
        'image_url' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1618354691792-d1d42acfd860?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Buttercream White, Dusty Rose',
        'material' => '60% Cotton, 40% Polyester Soft Fleece',
        'sizes' => 'XS, S, M, L',
        'rating' => 4.74,
        'review_count' => 73,
        'featured' => 0,
        'reviews' => [
            ['Ananya Pandey', 5, 'Cute boxy crop! Pairs wonderfully with high-waisted sweatpants and jeans.']
        ]
    ],
    [
        'sku' => 'HUD-009',
        'name' => "AllSaints Underground Acid-Washed Charcoal Fleece Hoodie",
        'brand' => 'AllSaints',
        'category_slug' => 'hoodies',
        'category_name' => 'Hoodies',
        'subcategory' => 'Pullover Hoodies',
        'description' => "Distressed acid-washed finish with bleached edge accents. Ribbed sleeve gussets, gunmetal drawcord tips, and a relaxed streetwear drop shoulder drape.",
        'price' => 3490.00,
        'mrp' => 5490.00,
        'stock' => 14,
        'image_url' => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Acid Washed Charcoal',
        'material' => '100% Heavy Brushed Cotton',
        'sizes' => 'S, M, L, XL',
        'rating' => 4.89,
        'review_count' => 57,
        'featured' => 0,
        'reviews' => [
            ['Rajat Barmecha', 5, 'The acid wash looks artistic and high-end. Heavy, warm fabric.']
        ]
    ],
    [
        'sku' => 'HUD-010',
        'name' => "Nike Sportswear Tech Fleece Full-Zip Windrunner Hoodie (Sold Out)",
        'brand' => 'Nike',
        'category_slug' => 'hoodies',
        'category_name' => 'Hoodies',
        'subcategory' => 'Zip-Up Hoodies',
        'description' => "Engineered double-sided spacer fabric that provides lightweight warmth without bulk. Zippered arm sleeve tech pocket with taped frame, 4-panel streamlined hood, and modern contoured fit.",
        'price' => 3995.00,
        'mrp' => 5995.00,
        'stock' => 0, // OUT OF STOCK TEST PRODUCT FOR HOODIES
        'image_url' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Dark Grey Heather / Black, Pure Platinum',
        'material' => '66% Cotton, 34% Polyester Tech Fleece',
        'sizes' => 'S, M, L, XL',
        'rating' => 4.97,
        'review_count' => 310,
        'featured' => 0,
        'reviews' => [
            ['Irfan Khan', 5, 'The legendary Nike Tech Fleece. Sleekest jacket hoodie ever made. Restock soon!']
        ]
    ],

    // =========================================================================
    // 7. WATCHES (10 Products)
    // =========================================================================
    [
        'sku' => 'WCH-001',
        'name' => "Fossil Grant Chronograph Dark Brown Leather Watch",
        'brand' => 'Fossil',
        'category_slug' => 'watches',
        'category_name' => 'Watches',
        'subcategory' => 'Chronograph Watches',
        'description' => "Roman numeral indices and triple sub-dial chronograph stopwatch movement. Housed in a 44mm polished stainless steel case with scratch-resistant mineral crystal and an interchangeable 22mm genuine dark brown leather strap. 5 ATM water resistance.",
        'price' => 7495.00,
        'mrp' => 12495.00,
        'stock' => 18,
        'image_url' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Dark Brown Leather / Blue Dial, Black / Silver',
        'material' => 'Stainless Steel Case, Genuine Calfskin Leather Strap',
        'sizes' => 'One Size (44mm Case)',
        'rating' => 4.88,
        'review_count' => 240,
        'featured' => 1,
        'reviews' => [
            ['Sanjay Dutt', 5, 'Classic Roman numerals with rich blue dial. Fossil leather strap is soft and comfortable.'],
            ['Arun Jaitley', 5, 'Looks twice as expensive as it costs. Received numerous compliments.']
        ]
    ],
    [
        'sku' => 'WCH-002',
        'name' => "Casio Vintage Digital Gold Stainless Steel Watch",
        'brand' => 'Casio',
        'category_slug' => 'watches',
        'category_name' => 'Watches',
        'subcategory' => 'Vintage Digital',
        'description' => "The iconic 1980s retro digital wristwatch (A168WG). Features gold-ion plated stainless steel bracelet, micro-light electroluminescent backlight, 1/100 second stopwatch, daily alarm, hourly time signal, and 7-year battery life.",
        'price' => 3995.00,
        'mrp' => 5495.00,
        'stock' => 32,
        'image_url' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Gold Tone, Silver Chrome',
        'material' => 'Gold-Ion Plated Stainless Steel',
        'sizes' => 'One Size (38mm Adjustable)',
        'rating' => 4.95,
        'review_count' => 410,
        'featured' => 1,
        'reviews' => [
            ['Hardik Pandya', 5, 'Timeless retro drip. Light, accurate, and catches light brilliantly.']
        ]
    ],
    [
        'sku' => 'WCH-003',
        'name' => "Titan Edge Ultra-Slim Ceramic Analog Watch",
        'brand' => 'Titan',
        'category_slug' => 'watches',
        'category_name' => 'Watches',
        'subcategory' => 'Automatic Watches',
        'description' => "One of the slimmest quartz watches in the world with a razor-thin 4.4mm ceramic case profile. Sapphire crystal glass with anti-reflective coating, minimalist dial with two hands, and hypoallergenic ceramic link bracelet with butterfly clasp.",
        'price' => 11995.00,
        'mrp' => 16995.00,
        'stock' => 14,
        'image_url' => 'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Midnight Ceramic Black, Anthracite Grey',
        'material' => 'High-Tech Ceramic Case, Sapphire Crystal',
        'sizes' => 'One Size (40mm Case)',
        'rating' => 4.90,
        'review_count' => 128,
        'featured' => 1,
        'reviews' => [
            ['Narayana Murthy', 5, 'Astonishing engineering. Weightless on the wrist and scratch-proof ceramic.']
        ]
    ],
    [
        'sku' => 'WCH-004',
        'name' => "Daniel Wellington Classic Sheffield White Dial Mesh Watch",
        'brand' => 'Daniel Wellington',
        'category_slug' => 'watches',
        'category_name' => 'Watches',
        'subcategory' => 'Leather Strap Watches',
        'description' => "Pure Scandinavian minimalism. Eggshell white 40mm dial paired with an ultra-thin 6mm case and silver Milanese mesh magnetic strap. Reliable Japanese quartz movement.",
        'price' => 8499.00,
        'mrp' => 12999.00,
        'stock' => 16,
        'image_url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Silver Mesh / White Dial, Rose Gold Mesh',
        'material' => '316L Stainless Steel',
        'sizes' => 'One Size (40mm)',
        'rating' => 4.75,
        'review_count' => 85,
        'featured' => 0,
        'reviews' => [
            ['Pooja Hegde', 5, 'Super sleek watch that slides effortlessly under dress cuffs. Beautiful finish.']
        ]
    ],
    [
        'sku' => 'WCH-005',
        'name' => "Seiko 5 Sports Automatic Mechanical Field Watch",
        'brand' => 'Seiko',
        'category_slug' => 'watches',
        'category_name' => 'Watches',
        'subcategory' => 'Automatic Watches',
        'description' => "Powered by the celebrated in-house Caliber 4R36 automatic movement with 41-hour power reserve. See-through exhibition case back, LumiBrite luminous hands and markers, day-date calendar, and olive ballistic nylon strap. 100M water resistance.",
        'price' => 14900.00,
        'mrp' => 21000.00,
        'stock' => 12,
        'image_url' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Military Green / Matte Case, Desert Sand',
        'material' => 'Bead-Blasted Stainless Steel, Hardlex Crystal',
        'sizes' => 'One Size (39.4mm Case)',
        'rating' => 4.97,
        'review_count' => 190,
        'featured' => 1,
        'reviews' => [
            ['Col. Rathore', 5, 'A true mechanical workhorse. Automatic winding is smooth and lume glows bright all night.']
        ]
    ],
    [
        'sku' => 'WCH-006',
        'name' => "Timex Expedition Scout Tactical Olive Strap Watch",
        'brand' => 'Timex',
        'category_slug' => 'watches',
        'category_name' => 'Watches',
        'subcategory' => 'Leather Strap Watches',
        'description' => "Classic outdoor field watch with military 24-hour inner markings and quick-date window at 3 o'clock. Patented INDIGLO electroluminescent night-light illuminates dial uniformly with a push of the crown.",
        'price' => 3495.00,
        'mrp' => 4995.00,
        'stock' => 25,
        'image_url' => 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1539185441755-769473a23570?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Matte Black / Olive Canvas, Gunmetal / Tan Leather',
        'material' => 'Brass Case with Matte Finish, Tactical Canvas Strap',
        'sizes' => 'One Size (40mm)',
        'rating' => 4.82,
        'review_count' => 110,
        'featured' => 0,
        'reviews' => [
            ['Raghav Bahl', 5, 'The INDIGLO night light is unbeatable. Perfect rugged weekend watch.']
        ]
    ],
    [
        'sku' => 'WCH-007',
        'name' => "Michael Kors Runway Rose Gold Crystal Link Dress Watch",
        'brand' => 'Michael Kors',
        'category_slug' => 'watches',
        'category_name' => 'Watches',
        'subcategory' => 'Leather Strap Watches',
        'description' => "Glamorous 38mm runway timepiece finished in radiant rose-gold tone stainless steel. Bezel encrusted with pavé crystal accents, sunray champagne dial, and polished three-link bracelet with deployant clasp.",
        'price' => 9995.00,
        'mrp' => 15995.00,
        'stock' => 15,
        'image_url' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1526045612212-70caf35c14df?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Rose Gold Tone, Yellow Gold Tone',
        'material' => 'Rose-Gold PVD Coated Stainless Steel',
        'sizes' => 'One Size (38mm)',
        'rating' => 4.86,
        'review_count' => 98,
        'featured' => 0,
        'reviews' => [
            ['Tara Sutaria', 5, 'Stunning statement piece. Shines beautifully under party lights.']
        ]
    ],
    [
        'sku' => 'WCH-008',
        'name' => "Citizen Eco-Drive Promaster 200M ISO Diver Watch",
        'brand' => 'Citizen',
        'category_slug' => 'watches',
        'category_name' => 'Watches',
        'subcategory' => 'Automatic Watches',
        'description' => "Powered by light—any light source—never needs a battery change with Eco-Drive technology. Unidirectional elapsed time bezel, screw-down crown and caseback, ISO compliant 200M water resistance, and polyurethane accordion diver strap.",
        'price' => 12900.00,
        'mrp' => 18500.00,
        'stock' => 14,
        'image_url' => 'https://images.unsplash.com/photo-1539185441755-769473a23570?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1518131672697-613becd4fab5?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Deep Sea Blue, Diver Black',
        'material' => 'Solid Stainless Steel, Anti-Reflective Mineral Crystal',
        'sizes' => 'One Size (44mm)',
        'rating' => 4.93,
        'review_count' => 145,
        'featured' => 0,
        'reviews' => [
            ['Capt. Sunil Rawat', 5, 'Legitimate dive watch. Lume is bright green and zero battery headaches thanks to Eco-Drive.']
        ]
    ],
    [
        'sku' => 'WCH-009',
        'name' => "Fossil Neutra Minimalist Chronograph Amber Glass Watch",
        'brand' => 'Fossil',
        'category_slug' => 'watches',
        'category_name' => 'Watches',
        'subcategory' => 'Chronograph Watches',
        'description' => "Mid-century modern design featuring a clean satin dial with stick hour markers and amber-tinted crystal glass. Sub-dials record seconds, minutes, and 24-hour time. Fastened with supple tan saddle leather strap.",
        'price' => 6995.00,
        'mrp' => 10995.00,
        'stock' => 17,
        'image_url' => 'https://images.unsplash.com/photo-1526045612212-70caf35c14df?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Amber Crystal / Saddle Tan Leather',
        'material' => 'Stainless Steel Case, Tan Genuine Leather',
        'sizes' => 'One Size (44mm)',
        'rating' => 4.78,
        'review_count' => 76,
        'featured' => 0,
        'reviews' => [
            ['Prateek Gandhi', 5, 'The amber crystal gives it a vintage warm glow in sunlight. Very stylish.']
        ]
    ],
    [
        'sku' => 'WCH-010',
        'name' => "Tissot Le Locle Powermatic 80 Swiss Automatic (Sold Out)",
        'brand' => 'Tissot',
        'category_slug' => 'watches',
        'category_name' => 'Watches',
        'subcategory' => 'Automatic Watches',
        'description' => "Swiss made luxury dress watch celebrating Tissot’s home in Le Locle. Features an extraordinary 80-hour power reserve automatic movement, guilloche Clous de Paris dial, Roman numerals, and butterfly folding clasp.",
        'price' => 24900.00,
        'mrp' => 38000.00,
        'stock' => 0, // OUT OF STOCK TEST PRODUCT FOR WATCHES
        'image_url' => 'https://images.unsplash.com/photo-1518131672697-613becd4fab5?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Silver Guilloche Dial / Alligator Pattern Strap',
        'material' => '316L Swiss Stainless Steel, Scratch-Resistant Sapphire',
        'sizes' => 'One Size (39.3mm)',
        'rating' => 4.99,
        'review_count' => 180,
        'featured' => 0,
        'reviews' => [
            ['Dr. Shashi Tharoor', 5, 'Horological excellence. The guilloche dial is hypnotic. Waiting for fresh inventory.']
        ]
    ],

    // =========================================================================
    // 8. GOGGLES / SUNGLASSES (10 Products)
    // =========================================================================
    [
        'sku' => 'GOG-001',
        'name' => "Ray-Ban Original Wayfarer Classic Polarized Sunglasses",
        'brand' => 'Ray-Ban',
        'category_slug' => 'sunglasses',
        'category_name' => 'Goggles / Sunglasses',
        'subcategory' => 'Wayfarer Sunglasses',
        'description' => "Since 1952, the RB2140 Wayfarer has been the most recognizable style in sunglasses history. Polished black acetate frame fitted with polarized G-15 green mineral glass lenses that block 100% of UVA/UVB rays and eliminate glare.",
        'price' => 6490.00,
        'mrp' => 9990.00,
        'stock' => 25,
        'image_url' => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1572635196237-14b3f281503f?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1508296695146-257a814070b4?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Gloss Black / G-15 Green, Tortoise / Brown',
        'material' => 'Handcrafted Italian Acetate Frame, Crystal Lenses',
        'sizes' => 'Standard (50mm)',
        'rating' => 4.95,
        'review_count' => 320,
        'featured' => 1,
        'reviews' => [
            ['Akshay Kumar', 5, 'The one and only original Wayfarer. Polarized lenses make driving crystal clear.'],
            ['Sanjay Gupta', 5, 'Heavy durable acetate and pristine optics. Worth every single rupee.']
        ]
    ],
    [
        'sku' => 'GOG-002',
        'name' => "Ray-Ban Aviator Classic Gold Frame Pilot Sunglasses",
        'brand' => 'Ray-Ban',
        'category_slug' => 'sunglasses',
        'category_name' => 'Goggles / Sunglasses',
        'subcategory' => 'Aviator Sunglasses',
        'description' => "Originally designed in 1937 for US military aviators. Iconic teardrop lenses mounted in an ultra-light gold monel metal wire frame. Features adjustable silicone nose pads and transparent temple tips.",
        'price' => 5990.00,
        'mrp' => 8990.00,
        'stock' => 20,
        'image_url' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1577803645773-f96470509666?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Arista Gold / G-15 Green, Gunmetal / Polarized Grey',
        'material' => 'Corrosion-Resistant Metal Alloy, Glass Lenses',
        'sizes' => 'Large (58mm)',
        'rating' => 4.92,
        'review_count' => 265,
        'featured' => 1,
        'reviews' => [
            ['Anil Kapoor', 5, 'Timeless pilot frame. Lightweight on the nose bridge with flawless UV shielding.']
        ]
    ],
    [
        'sku' => 'GOG-003',
        'name' => "Oakley Holbrook Matte Black Prizm Polarized Goggles",
        'brand' => 'Oakley',
        'category_slug' => 'sunglasses',
        'category_name' => 'Goggles / Sunglasses',
        'subcategory' => 'Sport Polarized',
        'description' => "Iconic square sport lifestyle silhouette inspired by 1940s Hollywood heroes. Injection-molded O Matter stress-resistant frame with metal bolt rivets. Equipped with Prizm Sapphire polarized lenses that dramatically boost color contrast.",
        'price' => 7890.00,
        'mrp' => 11490.00,
        'stock' => 16,
        'image_url' => 'https://images.unsplash.com/photo-1508296695146-257a814070b4?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1509695503492-413bc6d0a63a?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Matte Black / Prizm Black Polarized, Sapphire Iridium',
        'material' => 'O-Matter Lightweight Frame, Plutonite Lenses',
        'sizes' => 'Standard Fit (55mm)',
        'rating' => 4.94,
        'review_count' => 180,
        'featured' => 1,
        'reviews' => [
            ['Gaurav Gill', 5, 'Oakley Prizm lenses are unbeatable for driving and bright outdoors. Crisp clarity.']
        ]
    ],
    [
        'sku' => 'GOG-004',
        'name' => "Ray-Ban Round Metal Vintage Retro Wire Sunglasses",
        'brand' => 'Ray-Ban',
        'category_slug' => 'sunglasses',
        'category_name' => 'Goggles / Sunglasses',
        'subcategory' => 'Round Retro',
        'description' => "1960s counter-culture icon (RB3447). Defined by its circular crystal lenses, curved brow bar, thin tubular metal wire temples, and filigree bridge engraving.",
        'price' => 5490.00,
        'mrp' => 8490.00,
        'stock' => 18,
        'image_url' => 'https://images.unsplash.com/photo-1577803645773-f96470509666?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1473496169904-658ba7c44d8a?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Gold / Crystal Green, Copper / Brown',
        'material' => 'Lightweight Metal Wire Frame, UV400 Glass',
        'sizes' => 'Standard (50mm)',
        'rating' => 4.86,
        'review_count' => 134,
        'featured' => 0,
        'reviews' => [
            ['Alia Bhatt', 5, 'The retro round look is adorable! Very lightweight and does not press on the temples.']
        ]
    ],
    [
        'sku' => 'GOG-005',
        'name' => "Ray-Ban Clubmaster Classic Half-Rim Acetate Shades",
        'brand' => 'Ray-Ban',
        'category_slug' => 'sunglasses',
        'category_name' => 'Goggles / Sunglasses',
        'subcategory' => 'Wayfarer Sunglasses',
        'description' => "Browline retro silhouette featuring black acetate top rim with gold-tone metal underwire rim. Signature metal rivets at the temple corners and adjustable nose pads for bespoke comfort.",
        'price' => 6190.00,
        'mrp' => 9290.00,
        'stock' => 17,
        'image_url' => 'https://images.unsplash.com/photo-1473496169904-658ba7c44d8a?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1582142306909-195724d33ffc?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Ebony Black & Gold / G-15 Green, Mock Tortoise',
        'material' => 'Acetate Browline & Metal Frame',
        'sizes' => 'Standard (51mm)',
        'rating' => 4.90,
        'review_count' => 155,
        'featured' => 0,
        'reviews' => [
            ['Farhan Akhtar', 5, 'Intellectual vintage vibe. Fits squarely on the face and blocks glare seamlessly.']
        ]
    ],
    [
        'sku' => 'GOG-006',
        'name' => "Oakley Radar EV Path Sport Cycling & Driving Goggles",
        'brand' => 'Oakley',
        'category_slug' => 'sunglasses',
        'category_name' => 'Goggles / Sunglasses',
        'subcategory' => 'Sport Polarized',
        'description' => "Aerodynamic shield sports goggles offering extended upper field of view. Unobtainium nose pads and ear socks increase grip with perspiration. Vent channels prevent fogging in high-exertion sports.",
        'price' => 8990.00,
        'mrp' => 13490.00,
        'stock' => 15,
        'image_url' => 'https://images.unsplash.com/photo-1509695503492-413bc6d0a63a?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Polished White / Prizm Road, Matte Black / Trail Torch',
        'material' => 'O-Matter Sport Frame, Polycarbonate Shield',
        'sizes' => 'One Size Sport Shield',
        'rating' => 4.93,
        'review_count' => 112,
        'featured' => 0,
        'reviews' => [
            ['Virat Kohli', 5, 'Worn during cricket and cycling. Completely stays locked to the face without budging.']
        ]
    ],
    [
        'sku' => 'GOG-007',
        'name' => "Prada Symbole Geometric Cat-Eye Luxury Sunglasses",
        'brand' => 'Prada',
        'category_slug' => 'sunglasses',
        'category_name' => 'Goggles / Sunglasses',
        'subcategory' => 'Wayfarer Sunglasses',
        'description' => "High fashion angular cat-eye sunglasses featuring three-dimensional temples with iconic Prada triangular lettering logo. Deep bevelled front silhouette with 100% UVA/UVB gradient dark grey lenses.",
        'price' => 12900.00,
        'mrp' => 18900.00,
        'stock' => 11,
        'image_url' => 'https://images.unsplash.com/photo-1582142306909-195724d33ffc?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1516257984-b1b4d707412e?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Gloss Black / Dark Grey, Tortoise Shell',
        'material' => 'Premium Italian Thick Acetate',
        'sizes' => 'Standard (54mm)',
        'rating' => 4.96,
        'review_count' => 78,
        'featured' => 1,
        'reviews' => [
            ['Kareena Kapoor Khan', 5, 'Absolute runway glamour. The triangular temples are instantly recognizable.']
        ]
    ],
    [
        'sku' => 'GOG-008',
        'name' => "Dior Rimless Shield Y2K Mirrored Sport Goggles",
        'brand' => 'Dior',
        'category_slug' => 'sunglasses',
        'category_name' => 'Goggles / Sunglasses',
        'subcategory' => 'Sport Polarized',
        'description' => "Futuristic early-2000s mask sunglasses constructed from a single wraparound mirrored silver lens. Laser-engraved star rivets at the bridge and slim metallic temples with CD logo accents.",
        'price' => 14500.00,
        'mrp' => 22000.00,
        'stock' => 9,
        'image_url' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1589782182703-2aaa69037b5b?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Silver Mirror Mask, Rose Gold Flash',
        'material' => 'Ultra-Light Titanium Temples, Polycarbonate Mask',
        'sizes' => 'One Size Mask',
        'rating' => 4.88,
        'review_count' => 47,
        'featured' => 0,
        'reviews' => [
            ['Natasha Poonawalla', 5, 'Peak Y2K aesthetic! Pure luxury craftsmanship with zero visual distortion.']
        ]
    ],
    [
        'sku' => 'GOG-009',
        'name' => "Ray-Ban Hexagonal Flat Lenses Gold Wireframe Sunglasses",
        'brand' => 'Ray-Ban',
        'category_slug' => 'sunglasses',
        'category_name' => 'Goggles / Sunglasses',
        'subcategory' => 'Round Retro',
        'description' => "The evolution of the classic round: a fresh geometric hexagonal profile with coin-edge embossed wire perimeter. Crystal flat lenses in G-15 bottle green shade.",
        'price' => 5890.00,
        'mrp' => 8890.00,
        'stock' => 19,
        'image_url' => 'https://images.unsplash.com/photo-1516257984-b1b4d707412e?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Polished Gold / Green G-15, Black / Crystal Grey',
        'material' => 'Electroplated Metal Wire, Mineral Flat Crystal',
        'sizes' => 'Standard (51mm)',
        'rating' => 4.84,
        'review_count' => 125,
        'featured' => 0,
        'reviews' => [
            ['Ishaan Khatter', 5, 'Geometric edges give it a very contemporary edge. Super clean frames.']
        ]
    ],
    [
        'sku' => 'GOG-010',
        'name' => "Maui Jim Ocean Wave Polarized Driving Goggles (Sold Out)",
        'brand' => 'Maui Jim',
        'category_slug' => 'sunglasses',
        'category_name' => 'Goggles / Sunglasses',
        'subcategory' => 'Sport Polarized',
        'description' => "World-renowned PolarizedPlus2 lens technology wipes out 99.9% of glare and enhances vibrant island colors. Lightweight nylon frame with embedded rubber nose pads for maximum driving comfort.",
        'price' => 11990.00,
        'mrp' => 16990.00,
        'stock' => 0, // OUT OF STOCK TEST PRODUCT FOR GOGGLES / SUNGLASSES
        'image_url' => 'https://images.unsplash.com/photo-1589782182703-2aaa69037b5b?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1572635196237-14b3f281503f?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Matte Tortoise / HCL Bronze, Gloss Black / Neutral Grey',
        'material' => 'SuperThin Glass Lenses, Grilamid Nylon Frame',
        'sizes' => 'Standard (57mm)',
        'rating' => 4.98,
        'review_count' => 160,
        'featured' => 0,
        'reviews' => [
            ['Adar Poonawalla', 5, 'Best polarized lenses on Earth without exception. Waiting for inventory!']
        ]
    ],

    // =========================================================================
    // 9. BAGS AND PURSES (10 Products)
    // =========================================================================
    [
        'sku' => 'BAG-001',
        'name' => "Michael Kors Jet Set Large Saffiano Leather Shopper Tote",
        'brand' => 'Michael Kors',
        'category_slug' => 'bags-and-purses',
        'category_name' => 'Bags and Purses',
        'subcategory' => 'Tote Bags',
        'description' => "Constructed from cross-hatch scratch-resistant Saffiano genuine leather. Top zip closure keeps laptop and valuables secure. Features twin adjustable shoulder straps, gleaming gold-tone hardware, MK circle charm medallion, and multi-pocket organizer interior.",
        'price' => 12499.00,
        'mrp' => 19999.00,
        'stock' => 17,
        'image_url' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Luggage Tan, Classic Black, Powder Blush',
        'material' => '100% Saffiano Genuine Leather, Signature Polyester Lining',
        'sizes' => 'Large (15\" W x 11\" H x 5.5\" D)',
        'rating' => 4.92,
        'review_count' => 210,
        'featured' => 1,
        'reviews' => [
            ['Deepika Padukone', 5, 'My everyday go-to work tote! Fits a 15-inch laptop, water bottle and diary with room to spare.'],
            ['Shweta Nanda', 5, 'Saffiano leather is virtually indestructible. Gold hardware has not tarnished at all.']
        ]
    ],
    [
        'sku' => 'BAG-002',
        'name' => "Coach Willow Polished Pebbled Leather Shoulder Handbag",
        'brand' => 'Coach New York',
        'category_slug' => 'bags-and-purses',
        'category_name' => 'Bags and Purses',
        'subcategory' => 'Shoulder Bags',
        'description' => "The quintessential carryall with room for daily necessities. Crafted in buttery polished pebble leather with signature turnlock hardware. Features two open compartments, a secure central zip compartment, and detachable shoulder and crossbody straps.",
        'price' => 16900.00,
        'mrp' => 25900.00,
        'stock' => 14,
        'image_url' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1591561954557-26941169b49e?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Chalk White, Saddle Brown, Black',
        'material' => '100% Polished Pebble Leather',
        'sizes' => 'Medium (12.5\" W x 9\" H x 4.5\" D)',
        'rating' => 4.95,
        'review_count' => 140,
        'featured' => 1,
        'reviews' => [
            ['Sonam Kapoor', 5, 'The leather quality is sublime. Perfect balance of elegance and everyday practicality.']
        ]
    ],
    [
        'sku' => 'BAG-003',
        'name' => "Fjallraven Kanken Water-Resistant Everyday Backpack",
        'brand' => 'Fjallraven',
        'category_slug' => 'bags-and-purses',
        'category_name' => 'Bags and Purses',
        'subcategory' => 'Backpacks',
        'description' => "Iconic Scandinavian school and travel pack crafted from durable, water-resistant Vinylon F fabric. Dual top carry handles, ergonomic shoulder straps, removable PE foam seat cushion pad, and reflective front logo roundel.",
        'price' => 5499.00,
        'mrp' => 7999.00,
        'stock' => 28,
        'image_url' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Ox Red, Frost Green, Ochre Yellow, Black',
        'material' => 'Vinylon F Hardwearing Fabric',
        'sizes' => 'Standard 16L Capacity',
        'rating' => 4.88,
        'review_count' => 380,
        'featured' => 1,
        'reviews' => [
            ['Aditi Rao Hydari', 5, 'Compact yet deceptively roomy. Perfect for flights and campus life.']
        ]
    ],
    [
        'sku' => 'BAG-004',
        'name' => "Kate Spade New York Staci Mini Crossbody Clutch Purse",
        'brand' => 'Kate Spade',
        'category_slug' => 'bags-and-purses',
        'category_name' => 'Bags and Purses',
        'subcategory' => 'Purses & Clutches',
        'description' => "Charming dome-shaped crossbody clutch purse crafted in textured leather with pale gold foil-embossed logo. Two-way spade jacquard interior lining with dedicated credit card slots and zip coin pocket.",
        'price' => 6990.00,
        'mrp' => 11990.00,
        'stock' => 18,
        'image_url' => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1600857062241-98e5dba7f214?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Blush Pink, Warm Beige, Black',
        'material' => 'Textured Saffiano Cowhide Leather',
        'sizes' => 'Mini (8\" W x 5.5\" H x 2.8\" D)',
        'rating' => 4.82,
        'review_count' => 95,
        'featured' => 0,
        'reviews' => [
            ['Janhvi Kapoor', 5, 'The cutest mini purse for brunches and dinner dates. Holds phone, keys, and lipstick easily.']
        ]
    ],
    [
        'sku' => 'BAG-005',
        'name' => "Marc Jacobs The Tote Bag Heavyweight Canvas Shopper",
        'brand' => 'Marc Jacobs',
        'category_slug' => 'bags-and-purses',
        'category_name' => 'Bags and Purses',
        'subcategory' => 'Tote Bags',
        'description' => "The viral cult-favorite chunky canvas tote bag with bold screen-printed typography across the front. Chunky top zip closure, sturdy rolled cotton webbing handles, and detachable adjustable webbing crossbody strap.",
        'price' => 8490.00,
        'mrp' => 13490.00,
        'stock' => 20,
        'image_url' => 'https://images.unsplash.com/photo-1566150905458-1bf1fc113f0d?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Warm Beige / Black Print, Slate Slate',
        'material' => '100% Heavyweight Cotton Canvas',
        'sizes' => 'Medium (13\" W x 10.5\" H x 6\" D)',
        'rating' => 4.91,
        'review_count' => 175,
        'featured' => 1,
        'reviews' => [
            ['Ananya Panday', 5, 'Obsessed with this tote! Incredibly sturdy canvas and holds its shape effortlessly.']
        ]
    ],
    [
        'sku' => 'BAG-006',
        'name' => "Zara Croc-Embossed Baguette 90s Shoulder Bag",
        'brand' => 'Zara Woman',
        'category_slug' => 'bags-and-purses',
        'category_name' => 'Bags and Purses',
        'subcategory' => 'Shoulder Bags',
        'description' => "Sleek 1990s baguette silhouette with high-gloss crocodile embossed finish. Short wide shoulder strap designed to sit comfortably under the arm, silver metallic magnetic flap closure, and inner zip pocket.",
        'price' => 2490.00,
        'mrp' => 3790.00,
        'stock' => 22,
        'image_url' => 'https://images.unsplash.com/photo-1591561954557-26941169b49e?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Patent Black, Cream Ecru',
        'material' => '100% Croc-Embossed Vegan Leather',
        'sizes' => 'Standard Baguette (10\" W x 5\" H)',
        'rating' => 4.70,
        'review_count' => 84,
        'featured' => 0,
        'reviews' => [
            ['Khushi Kapoor', 5, 'Looks so chic for evening wear. The croc texture gives it such a rich look.']
        ]
    ],
    [
        'sku' => 'BAG-007',
        'name' => "Fossil Defender Vintage Heavy Leather Weekender Duffel",
        'brand' => 'Fossil',
        'category_slug' => 'bags-and-purses',
        'category_name' => 'Bags and Purses',
        'subcategory' => 'Backpacks',
        'description' => "Heirloom travel duffel bag built from rich waxy pull-up full-grain leather. Two-way brass zipper with leather pulls, padded laptop compartment, brass protective base feet, and thick cotton webbing shoulder strap.",
        'price' => 14995.00,
        'mrp' => 22995.00,
        'stock' => 11,
        'image_url' => 'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Cognac Brown, Vintage Black',
        'material' => '100% Full-Grain Vegetable Tanned Leather',
        'sizes' => 'Weekender (20\" W x 12\" H x 9\" D)',
        'rating' => 4.96,
        'review_count' => 62,
        'featured' => 0,
        'reviews' => [
            ['Karan Johar', 5, 'The leather smell and patina are top tier. Ideal companion for 3-day weekend trips.']
        ]
    ],
    [
        'sku' => 'BAG-008',
        'name' => "Saint Laurent LouLou Quilted Velvet Evening Chain Purse",
        'brand' => 'YSL Beauty & Accessories',
        'category_slug' => 'bags-and-purses',
        'category_name' => 'Bags and Purses',
        'subcategory' => 'Purses & Clutches',
        'description' => "Iconic Y-quilted plush velvet evening purse featuring interlocking metal YSL monogram insignia. Sliding curb chain strap can be worn double on the shoulder or single as a long crossbody. Magnetic snap flap.",
        'price' => 18900.00,
        'mrp' => 28900.00,
        'stock' => 10,
        'image_url' => 'https://images.unsplash.com/photo-1600857062241-98e5dba7f214?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Noir Black Velvet, Emerald Green Velvet',
        'material' => 'Silk-Blend Velvet, Gilt Brass Hardware',
        'sizes' => 'Small (9\" W x 6.5\" H x 3.5\" D)',
        'rating' => 4.98,
        'review_count' => 88,
        'featured' => 1,
        'reviews' => [
            ['Mira Rajput', 5, 'Breathtaking craftsmanship. The velvet is deep and opulent for cocktail events.']
        ]
    ],
    [
        'sku' => 'BAG-009',
        'name' => "Mango Slouchy Ruched Vegan Leather Hobo Handbag",
        'brand' => 'Mango',
        'category_slug' => 'bags-and-purses',
        'category_name' => 'Bags and Purses',
        'subcategory' => 'Shoulder Bags',
        'description' => "Cloud-soft gathered ruched detailing along the opening with a concealed magnetic closure. Ergonomically curved shoulder handle fits comfortably over coats and jackets. Lined in soft suede-touch microfiber.",
        'price' => 2990.00,
        'mrp' => 4490.00,
        'stock' => 20,
        'image_url' => 'https://images.unsplash.com/photo-1575032617751-6ddec2089882?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Camel Tan, Butter Milk, Black',
        'material' => 'Ultra-Soft Polyurethane Nappa Touch',
        'sizes' => 'One Size (14\" W x 9\" H)',
        'rating' => 4.76,
        'review_count' => 64,
        'featured' => 0,
        'reviews' => [
            ['Dia Mirza', 5, 'So soft and slouchy! Holds everything easily without feeling heavy on the shoulder.']
        ]
    ],
    [
        'sku' => 'BAG-010',
        'name' => "Coach Small Zip-Around Wristlet Wallet Purse (Sold Out)",
        'brand' => 'Coach New York',
        'category_slug' => 'bags-and-purses',
        'category_name' => 'Bags and Purses',
        'subcategory' => 'Purses & Clutches',
        'description' => "Signature coated canvas with refined crossgrain leather trim. Features 2 interior card slots, fabric lining, zip-around top closure, and attached leather wristlet strap.",
        'price' => 3900.00,
        'mrp' => 5900.00,
        'stock' => 0, // OUT OF STOCK TEST PRODUCT FOR BAGS
        'image_url' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Signature Khaki / Saddle, Black Smoke',
        'material' => 'Signature Coated Canvas & Refined Leather',
        'sizes' => 'One Size (6.25\" W x 4\" H)',
        'rating' => 4.89,
        'review_count' => 110,
        'featured' => 0,
        'reviews' => [
            ['Isha Ambani', 5, 'Everyday essential wristlet. Hope to buy another in Black Smoke once restocked!']
        ]
    ],

    // =========================================================================
    // 10. SHOES (10 Products)
    // =========================================================================
    [
        'sku' => 'SHS-001',
        'name' => "Nike Air Max 270 Responsive Lifestyle Running Shoes",
        'brand' => 'Nike',
        'category_slug' => 'shoes',
        'category_name' => 'Shoes',
        'subcategory' => 'Running Shoes',
        'description' => "Boasts Nike’s biggest heel Max Air unit (32mm) for a super-soft bounce with every step. Breathable engineered mesh upper with neoprene stretch bootie construction for a sock-like locked-in fit. Solid rubber outsole with dual density foam forefoot.",
        'price' => 7995.00,
        'mrp' => 12995.00,
        'stock' => 24,
        'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Triple Black, University Red / Black, White / Multi',
        'material' => 'Engineered Breathable Mesh, Max Air Unit Sole',
        'sizes' => '7, 8, 9, 10, 11',
        'rating' => 4.93,
        'review_count' => 310,
        'featured' => 1,
        'reviews' => [
            ['MS Dhoni', 5, 'Unbelievable heel cushion. You literally feel like you are walking on clouds all day.'],
            ['Rohit Sharma', 5, 'The 270 heel bounce is unmatched. Looks sleek with joggers and shorts.']
        ]
    ],
    [
        'sku' => 'SHS-002',
        'name' => "Nike Air Force 1 '07 Triple White Low Iconic Sneakers",
        'brand' => 'Nike',
        'category_slug' => 'shoes',
        'category_name' => 'Shoes',
        'subcategory' => 'Lifestyle Sneakers',
        'description' => "The legend lives on in the Nike Air Force 1 '07: crisp full-grain leather edges, pristine white-on-white palette, encapsulated Nike Air cushioning, and non-marking pivot-circle rubber outsole.",
        'price' => 7495.00,
        'mrp' => 9695.00,
        'stock' => 30,
        'image_url' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Triple White, Triple Black',
        'material' => '100% Genuine Full-Grain Leather Upper, Air Sole',
        'sizes' => '7, 8, 9, 10, 11, 12',
        'rating' => 4.98,
        'review_count' => 540,
        'featured' => 1,
        'reviews' => [
            ['Ranbir Kapoor', 5, 'The definitive sneaker in fashion. Clean, iconic, and matches literally any outfit on earth.']
        ]
    ],
    [
        'sku' => 'SHS-003',
        'name' => "Converse Chuck Taylor All Star High-Top Canvas Classic",
        'brand' => 'Converse',
        'category_slug' => 'shoes',
        'category_name' => 'Shoes',
        'subcategory' => 'High-Top Sneakers',
        'description' => "Unmistakable silhouette created in 1917: lightweight heavy-duty canvas upper, ankle patch with star logo, vulcanized rubber outsole with diamond tread pattern, and classic rubber toe cap.",
        'price' => 3799.00,
        'mrp' => 4999.00,
        'stock' => 28,
        'image_url' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Black / White, Optical White, Navy',
        'material' => '12 oz Heavy Duck Canvas, Vulcanized Rubber Sole',
        'sizes' => '6, 7, 8, 9, 10, 11',
        'rating' => 4.88,
        'review_count' => 380,
        'featured' => 1,
        'reviews' => [
            ['Sidhu Moose Wala', 5, 'Chuck 70 high-tops are an eternal classic. Fits true and looks vintage.']
        ]
    ],
    [
        'sku' => 'SHS-004',
        'name' => "Vans Old Skool Classic Suede Canvas Skate Shoes",
        'brand' => 'Vans',
        'category_slug' => 'shoes',
        'category_name' => 'Shoes',
        'subcategory' => 'Canvas Classics',
        'description' => "First known as the Vans #36, it debuted the famous Vans sidestripe. Features sturdy suede toe and heel counters, 10 oz canvas side quarters, re-enforced toe caps, padded collars, and signature rubber waffle outsoles.",
        'price' => 3499.00,
        'mrp' => 4999.00,
        'stock' => 22,
        'image_url' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1608231387042-66d1773070a5?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Black / White Sidestripe, Navy Blue / White',
        'material' => '56% Suede Leather, 44% Canvas Upper',
        'sizes' => '7, 8, 9, 10, 11',
        'rating' => 4.85,
        'review_count' => 220,
        'featured' => 0,
        'reviews' => [
            ['Tiger Shroff', 5, 'Super grippy waffle sole. Great board feel and timeless casual style.']
        ]
    ],
    [
        'sku' => 'SHS-005',
        'name' => "Adidas Ultraboost Light Responsive Energy Trainers",
        'brand' => 'Adidas',
        'category_slug' => 'shoes',
        'category_name' => 'Shoes',
        'subcategory' => 'Running Shoes',
        'description' => "Experience epic energy return with the 30% lighter Light BOOST material. PRIMEKNIT+ upper hugs foot with adaptive support, Continental Better Rubber outsole delivers wet and dry grip.",
        'price' => 9999.00,
        'mrp' => 17999.00,
        'stock' => 15,
        'image_url' => 'https://images.unsplash.com/photo-1560769629-975ec94e6a86?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Core Black, Cloud White, Solar Red',
        'material' => 'PRIMEKNIT+ Upper, Light BOOST Midsole',
        'sizes' => '7, 8, 9, 10, 11',
        'rating' => 4.96,
        'review_count' => 245,
        'featured' => 1,
        'reviews' => [
            ['Neeraj Chopra', 5, 'The energy bounce during sprints is incredible. Truly the elite running shoe.']
        ]
    ],
    [
        'sku' => 'SHS-006',
        'name' => "Puma Suede Classic XXI Low-Top Heritage Sneakers",
        'brand' => 'Puma',
        'category_slug' => 'shoes',
        'category_name' => 'Shoes',
        'subcategory' => 'Lifestyle Sneakers',
        'description' => "The sneaker that changed the game in 1968: full velvety suede upper with synthetic leather lining, gold metallic foil Puma Suede callout on quarter, and textured rubber cupsole.",
        'price' => 3899.00,
        'mrp' => 5999.00,
        'stock' => 20,
        'image_url' => 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Puma Black / White, Peacoat Navy',
        'material' => '100% Suede Leather Upper',
        'sizes' => '7, 8, 9, 10, 11',
        'rating' => 4.78,
        'review_count' => 115,
        'featured' => 0,
        'reviews' => [
            ['Sunil Chhetri', 5, 'The suede texture is plush. Great b-boy heritage and comfortable footbed.']
        ]
    ],
    [
        'sku' => 'SHS-007',
        'name' => "Clarks Batcombe Wingtip Brogue Handcrafted Leather Shoes",
        'brand' => 'Clarks',
        'category_slug' => 'shoes',
        'category_name' => 'Shoes',
        'subcategory' => 'Formal Shoes',
        'description' => "Distinguished British wingtip brogue shoes crafted in rich hand-burnished tan leather. Perforated medallion broguing, Cushion Plus dual-density technology, and cleated rubber welt sole.",
        'price' => 5999.00,
        'mrp' => 8999.00,
        'stock' => 16,
        'image_url' => 'https://images.unsplash.com/photo-1614252235316-8c857d38b5f4?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1607522370275-f14206abe5d3?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Burnished Tan Leather, Polished Black',
        'material' => '100% Genuine Full-Grain Cow Leather',
        'sizes' => '7, 8, 9, 10, 11',
        'rating' => 4.90,
        'review_count' => 86,
        'featured' => 0,
        'reviews' => [
            ['Justice Chandrachud', 5, 'Superior craftsmanship. The broguing is exquisite and cushion insoles prevent fatigue.']
        ]
    ],
    [
        'sku' => 'SHS-008',
        'name' => "Dr. Martens 2976 Smooth Leather Chelsea Boots",
        'brand' => 'Dr. Martens',
        'category_slug' => 'shoes',
        'category_name' => 'Shoes',
        'subcategory' => 'Formal Shoes',
        'description' => "Unisex Chelsea boots constructed with classic AirWair smooth leather, elasticated side gussets, yellow Goodyear welt stitching, and air-cushioned bouncing soles resistant to oil, fat, and slip.",
        'price' => 8999.00,
        'mrp' => 13999.00,
        'stock' => 14,
        'image_url' => 'https://images.unsplash.com/photo-1607522370275-f14206abe5d3?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1614252235316-8c857d38b5f4?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Smooth Black, Cherry Red',
        'material' => '100% Polished AirWair Leather, Goodyear Welted Sole',
        'sizes' => '7, 8, 9, 10, 11',
        'rating' => 4.92,
        'review_count' => 170,
        'featured' => 0,
        'reviews' => [
            ['Karan Kundrra', 5, 'Indestructible Docs Chelsea boots. Once broken in, you will never want to take them off.']
        ]
    ],
    [
        'sku' => 'SHS-009',
        'name' => "Toms Classic Alpargata Slip-On Canvas Loafers",
        'brand' => 'Toms',
        'category_slug' => 'shoes',
        'category_name' => 'Shoes',
        'subcategory' => 'Canvas Classics',
        'description' => "The original slip-on alpargata with elastic 'v' for easy on and off. Breathable natural canvas upper with removable OrthoLite Eco LT Hybrid insole for ultra-lightweight all-day comfort.",
        'price' => 2499.00,
        'mrp' => 3799.00,
        'stock' => 25,
        'image_url' => 'https://images.unsplash.com/photo-1584735935682-2f2b69dff9d2?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Ash Grey, Natural Burlap, Navy Blue',
        'material' => '100% Heritage Canvas, Rubber Outsole',
        'sizes' => '7, 8, 9, 10, 11',
        'rating' => 4.75,
        'review_count' => 98,
        'featured' => 0,
        'reviews' => [
            ['Kunal Nayyar', 5, 'The easiest shoe to slip on and go. So lightweight you forget you have shoes on.']
        ]
    ],
    [
        'sku' => 'SHS-010',
        'name' => "Balenciaga Triple S Chunky Dad Sneaker (Sold Out)",
        'brand' => 'Balenciaga',
        'category_slug' => 'shoes',
        'category_name' => 'Shoes',
        'subcategory' => 'Lifestyle Sneakers',
        'description' => "The pioneer of oversized high-fashion sneakers: 3-layer stacked complex outsole molded from running, basketball, and track shoes. Embroidered shoe size on the toe edge and bicolour athletic laces.",
        'price' => 19999.00,
        'mrp' => 32000.00,
        'stock' => 0, // OUT OF STOCK TEST PRODUCT FOR SHOES
        'image_url' => 'https://images.unsplash.com/photo-1539185441755-769473a23570?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Triple White / Clear Sole, Washed Grey',
        'material' => 'Mesh, Calfskin Leather, Triple Stack Rubber',
        'sizes' => '8, 9, 10, 11',
        'rating' => 4.97,
        'review_count' => 110,
        'featured' => 0,
        'reviews' => [
            ['Karan Aujla', 5, 'Heavy chunky statement kicks. The clear sole edition is legendary. Please restock size 10!']
        ]
    ],

    // =========================================================================
    // 11. ACCESSORIES (10 Products)
    // =========================================================================
    [
        'sku' => 'ACC-001',
        'name' => "Tiffany & Co. 925 Sterling Silver Minimalist Pendant Necklace",
        'brand' => 'Tiffany & Co.',
        'category_slug' => 'accessories',
        'category_name' => 'Accessories',
        'subcategory' => 'Necklaces & Pendants',
        'description' => "Delicate cable chain suspended with an understated round solitaire pendant. Crafted in genuine stamped 925 sterling silver with rhodium anti-tarnish protective plating and secure spring ring clasp.",
        'price' => 4990.00,
        'mrp' => 7990.00,
        'stock' => 20,
        'image_url' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Sterling Silver, 18K Yellow Gold Vermeil',
        'material' => '925 Solid Sterling Silver, Cubic Zirconia Solitaire',
        'sizes' => 'Adjustable 16\"-18\" Chain',
        'rating' => 4.95,
        'review_count' => 178,
        'featured' => 1,
        'reviews' => [
            ['Anushka Sharma', 5, 'Dainty and breathtaking. Has not tarnished despite continuous wear.'],
            ['Kiara Advani', 5, 'The perfect everyday pendant. Elegant and sparkling.']
        ]
    ],
    [
        'sku' => 'ACC-002',
        'name' => "Tommy Hilfiger Full-Grain Italian Leather Classic Belt",
        'brand' => 'Tommy Hilfiger',
        'category_slug' => 'accessories',
        'category_name' => 'Accessories',
        'subcategory' => 'Leather Belts',
        'description' => "Crafted from 35mm wide vegetable-tanned Italian cowhide leather. Features brushed satin nickel square pin-buckle, beveled dyed edges, and signature embossed enamel flag on the leather keeper loop.",
        'price' => 1899.00,
        'mrp' => 2999.00,
        'stock' => 25,
        'image_url' => 'https://images.unsplash.com/photo-1611591475819-79b8b7389828?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1516826957135-700dedea698c?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Cognac Tan, Jet Black',
        'material' => '100% Genuine Full-Grain Italian Leather',
        'sizes' => '32, 34, 36, 38, 40',
        'rating' => 4.88,
        'review_count' => 140,
        'featured' => 1,
        'reviews' => [
            ['Gautam Gambhir', 5, 'Thick full-grain leather that does not crack or split. Excellent belt.']
        ]
    ],
    [
        'sku' => 'ACC-003',
        'name' => "Bellroy Hide & Seek RFID-Blocking Slim Leather Wallet",
        'brand' => 'Bellroy',
        'category_slug' => 'accessories',
        'category_name' => 'Accessories',
        'subcategory' => 'Wallets',
        'description' => "Engineered to hold 5-12 cards and flat bills without pocket bulge. Features hidden rear banknote compartment for large currencies, protected RFID blocking lining, and premium environmentally certified leather.",
        'price' => 3890.00,
        'mrp' => 5490.00,
        'stock' => 22,
        'image_url' => 'https://images.unsplash.com/photo-1620656798579-1984d9e87dfa?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1605100804763-247f67b3557e?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Caramel Tan, Charcoal Black, Marine Blue',
        'material' => 'Environmentally Certified Top-Grain Leather',
        'sizes' => 'Slim Bifold (115 x 85 mm)',
        'rating' => 4.94,
        'review_count' => 195,
        'featured' => 1,
        'reviews' => [
            ['Raghuram Rajan', 5, 'Brilliant minimalist wallet. The hidden cash flap is genius.']
        ]
    ],
    [
        'sku' => 'ACC-004',
        'name' => "Mejuri 18K Gold Vermeil Chunky Paperclip Chain Bracelet",
        'brand' => 'Mejuri',
        'category_slug' => 'accessories',
        'category_name' => 'Accessories',
        'subcategory' => 'Bracelets & Cuffs',
        'description' => "Elongated interlocking paperclip link bracelet crafted in thick 18K gold vermeil (2.5 microns) over solid 925 sterling silver. Includes sturdy lobster claw clasp with branded tag.",
        'price' => 3490.00,
        'mrp' => 5290.00,
        'stock' => 19,
        'image_url' => 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => '18K Yellow Gold Vermeil, 925 Silver',
        'material' => '18K Gold Vermeil over 925 Sterling Silver',
        'sizes' => '7\" Length with 1\" Extender',
        'rating' => 4.89,
        'review_count' => 110,
        'featured' => 0,
        'reviews' => [
            ['Kriti Sanon', 5, 'The paperclip link is so stylish. Thick gold layer has not worn off.']
        ]
    ],
    [
        'sku' => 'ACC-005',
        'name' => "New Era 9FORTY New York Yankees Cotton Baseball Cap",
        'brand' => 'New Era',
        'category_slug' => 'accessories',
        'category_name' => 'Accessories',
        'subcategory' => 'Caps & Hats',
        'description' => "The world’s most iconic ballcap: structured 6-panel contoured crown, curved visor visor brim, 3D raised embroidered NY Yankees logo, and adjustable metal buckle cloth strapback.",
        'price' => 1699.00,
        'mrp' => 2499.00,
        'stock' => 35,
        'image_url' => 'https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1520903920243-00d872a2d1c9?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Navy / White Logo, Black / Black, Olive',
        'material' => '100% Cotton Woven Twill',
        'sizes' => 'One Size Fits All (Adjustable)',
        'rating' => 4.92,
        'review_count' => 320,
        'featured' => 0,
        'reviews' => [
            ['Zayn Malik', 5, 'Original New Era cap. The crown structure and curved bill fit flawlessly.']
        ]
    ],
    [
        'sku' => 'ACC-006',
        'name' => "Burberry Classic Vintage Check Pure Cashmere Scarf",
        'brand' => 'Burberry',
        'category_slug' => 'accessories',
        'category_name' => 'Accessories',
        'subcategory' => 'Caps & Hats',
        'description' => "Woven on traditional looms at a 200-year-old Scottish mill. Made from 100% fine Mongolian cashmere washed in local spring water and brushed with teasels for an ultra-soft raised ripple finish. Fringed hems.",
        'price' => 8990.00,
        'mrp' => 14990.00,
        'stock' => 12,
        'image_url' => 'https://images.unsplash.com/photo-1520903920243-00d872a2d1c9?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Archive Beige Classic Check, Charcoal Check',
        'material' => '100% Pure Scottish Cashmere',
        'sizes' => '168 x 30 cm',
        'rating' => 4.97,
        'review_count' => 95,
        'featured' => 1,
        'reviews' => [
            ['Aishwarya Rai', 5, 'Cloud-like cashmere softness. The heritage check pattern is timeless.']
        ]
    ],
    [
        'sku' => 'ACC-007',
        'name' => "Mejuri Bold Chunky 14K Gold Filled Tube Hoop Earrings",
        'brand' => 'Mejuri',
        'category_slug' => 'accessories',
        'category_name' => 'Accessories',
        'subcategory' => 'Bracelets & Cuffs',
        'description' => "Hollow-tube chunky statement hoops engineered for feather-light all-day comfort. 14K yellow gold filled exterior over brass with hypoallergenic surgical steel click-top posts.",
        'price' => 2490.00,
        'mrp' => 3790.00,
        'stock' => 22,
        'image_url' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => '14K Polished Gold',
        'material' => '14K Gold Filled, Surgical Steel Posts',
        'sizes' => '25mm Diameter (Medium)',
        'rating' => 4.87,
        'review_count' => 130,
        'featured' => 0,
        'reviews' => [
            ['Sara Ali Khan', 5, 'They look so chunky and bold yet weigh virtually nothing on the ears!']
        ]
    ],
    [
        'sku' => 'ACC-008',
        'name' => "Ralph Lauren 3-Pack Ribbed Organic Cotton Crew Socks",
        'brand' => 'Polo Ralph Lauren',
        'category_slug' => 'accessories',
        'category_name' => 'Accessories',
        'subcategory' => 'Caps & Hats',
        'description' => "Pack of three pairs of classic ribbed crew socks. Features cushioned sole for shock absorption, reinforced heel and toe, and signature polo player pony embroidery on the ribbed leg.",
        'price' => 1299.00,
        'mrp' => 1999.00,
        'stock' => 30,
        'image_url' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1611591475819-79b8b7389828?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'White / Navy / Black Pack',
        'material' => '85% Organic Cotton, 13% Polyester, 2% Spandex',
        'sizes' => 'One Size (Fits UK 7-11)',
        'rating' => 4.81,
        'review_count' => 88,
        'featured' => 0,
        'reviews' => [
            ['Kunal Rawal', 5, 'Super comfortable elastic and soft cushion sole. Best crew socks around.']
        ]
    ],
    [
        'sku' => 'ACC-009',
        'name' => "Fossil Natural Braided Leather Magnetic Steel Cuff",
        'brand' => 'Fossil',
        'category_slug' => 'accessories',
        'category_name' => 'Accessories',
        'subcategory' => 'Bracelets & Cuffs',
        'description' => "Multi-strand genuine cowhide braided leather wristband. Fastened with a high-polish stainless steel magnetic slide-lock clasp with laser-etched Fossil insignia.",
        'price' => 1995.00,
        'mrp' => 2995.00,
        'stock' => 18,
        'image_url' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Cognac Brown / Steel, Jet Black / Smoke Steel',
        'material' => 'Genuine Cowhide Leather, 316L Stainless Steel',
        'sizes' => 'Medium (19 cm), Large (21 cm)',
        'rating' => 4.76,
        'review_count' => 64,
        'featured' => 0,
        'reviews' => [
            ['Milind Soman', 5, 'Rugged masculine leather cuff. The magnetic clasp snaps on securely.']
        ]
    ],
    [
        'sku' => 'ACC-010',
        'name' => "Montblanc Meisterstuck Full-Grain Leather Cardholder (Sold Out)",
        'brand' => 'Montblanc',
        'category_slug' => 'accessories',
        'category_name' => 'Accessories',
        'subcategory' => 'Wallets',
        'description' => "Crafted from European full-grain cowhide with unique Montblanc deep shine finish. 6 credit card slots, central banknote or receipt pocket, and palladium-finish snowcap emblem ring.",
        'price' => 7490.00,
        'mrp' => 11500.00,
        'stock' => 0, // OUT OF STOCK TEST PRODUCT FOR ACCESSORIES
        'image_url' => 'https://images.unsplash.com/photo-1516826957135-700dedea698c?auto=format&fit=crop&w=800&q=80',
        'gallery' => [
            'https://images.unsplash.com/photo-1620656798579-1984d9e87dfa?auto=format&fit=crop&w=800&q=80'
        ],
        'color' => 'Montblanc Deep Shine Black',
        'material' => 'European Full-Grain Cowhide, Jacquard Lining',
        'sizes' => 'Pocket Cardholder (10 x 8 cm)',
        'rating' => 4.99,
        'review_count' => 140,
        'featured' => 0,
        'reviews' => [
            ['Anand Mahindra', 5, 'Masterpiece of luxury leather goods. Waiting for restock.']
        ]
    ]
];
