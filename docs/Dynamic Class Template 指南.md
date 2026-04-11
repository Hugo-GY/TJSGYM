# Dynamic Class Template - 使用指南

## 概述

统一的产品页面模板系统，支持通过单一模板动态加载任意课程产品数据。

**核心优势：**
- ✅ 零代码添加新产品
- ✅ 统一的视觉和功能体验
- ✅ 自动缓存优化性能
- ✅ 完整的移动端支持

---

## 快速开始（3 步）

### 第 1 步：在 WooCommerce 创建产品

1. 进入 **Products > Add New**
2. 设置产品标题（如 "Tiddler Gym"）
3. 设置 **Permalink/Slug**（如 `tiddler-gym`）
4. 产品类型选择 **Variable Product**
5. 添加变体（时间、价格、库存）
6. 在 **Product Categories** 中分配分类：
   - `tiddler-gym`
   - `toddler-gym`
   - `mini-gym`
   - `gymnastics`

### 第 2 步：配置 ACF 字段（可选但推荐）

在产品编辑页面填写以下字段：

| 字段名 | 类型 | 说明 |
|--------|------|------|
| `age_range` | Text | 年龄范围 (如 "6–12 Months") |
| `about_title` | WYSIWYG | 页面副标题 |
| `about_lead` | Textarea | 简短描述 |
| `about_content` | WYSIWYG | 详细内容 |
| `pay_type` | Select | `per_term` 或 `per_class` |
| `term_info` | Repeater | 学期信息（见下方） |
| `gallery_images` | Gallery | 图片画廊 |
| `booking_page_url` | URL | 自定义预订页链接 |

#### Term Info Repeater 结构：

```
- term_season: "Summer 2026"
- term_status: "Teaching now"
- term_weeks: "13 weeks"
- term_dates: "13 Apr – 21 May\n1 Jun – 16 Jul" (换行分隔)
- term_halfterm: "Half term: w/k 25 May · No class 4 May"
- term_payment_due: "Payment due by 12 March"
```

### 第 3 步：创建 WordPress 页面

1. 进入 **Pages > Add New**
2. 标题：如 "Tiddler Gym"
3. **Slug**：必须匹配产品 slug 或使用映射（见下方）
4. **Page Attributes > Template**：选择 **"Dynamic Class Detail"**
5. 发布页面 ✅

---

## URL Slug 映射规则

如果页面 slug 与产品 slug 不同，系统会自动尝试映射：

| 页面 Slug | 自动匹配到产品 |
|-----------|---------------|
| `/product/tiddler-gym` | → `tiddler-gym` |
| `/product/toddler-gym` | → `toddler-gym-product` |
| `/product/mini-gym` | → `mini-gym-product` |
| `/product/gymnastics` | → `gymnastics-product` |

**最佳实践：** 直接让页面 slug = 产品 slug（最简单）

---

## 示例 URL 结构

```
/product/tiddler-gym          → Tiddler Gym 课程页
/product/toddler-gym-product  → Toddler Gym 课程页
/product/mini-gym-product     → Mini Gym 课程页（未来）
/product/gymnastics-product   → Gymnastics 课程页（未来）
/product/new-class            → 新增任何课程...
```

---

## 图片资源组织

将课程图片放置在对应目录：

```
/wp-content/themes/tjs-gymnastics/assets/images/classes/
├── tiddler/
│   ├── gallery-1.jpg
│   ├── gallery-2.jpg
│   ├── gallery-3.jpg
│   ├── gallery-4.jpg
│   └── gallery-5.jpg
├── toddler/
│   └── ... (同上)
├── minigym/
│   └── ...
└── gymnastics/
    └── ...
```

**注意：** 系统现在支持 **3 种图片来源方式**（见下方 Gallery 管理章节）

---

## 📸 Gallery 图片管理（3 种方式）

系统采用 **三层优先级** 自动选择图片来源：

### **方式 1：ACF Gallery 字段（推荐 ⭐⭐⭐⭐⭐）**

**适用场景：** 需要完全控制图片顺序、单独设置 Alt 文本

**操作步骤：**
1. 编辑 WooCommerce 产品
2. 找到 **"Class Gallery & Media"** Meta Box
3. 点击 **"Add to Gallery"** 上传/选择图片
4. 拖拽调整顺序（第一张 = 主图）
5. 可点击每张图片编辑标题和 Alt 文本

**优点：**
- ✅ 拖拽排序，完全控制显示顺序
- ✅ 每张图可独立设置 Alt 文本（SEO 友好）
- ✅ 专用界面，不会与产品主图混淆
- ✅ 支持批量上传

**配置位置：**
```
产品编辑页 > Class Gallery & Media > Gallery Images
```

---

### **方式 2：WooCommerce 产品图库（简单 ⭐⭐⭐⭐）**

**适用场景：** 快速上线，复用已有产品图片

**操作步骤：**
1. 编辑 WooCommerce 产品
2. 在右侧边栏找到 **"Product gallery"** 区域
3. 点击 **"Add product gallery images"**
4. 选择/上传图片

**自动触发条件：**
- 当 ACF `gallery_images` 字段为空时
- 系统会自动读取 WC Product Gallery

**优点：**
- ✅ 无需额外 ACF 配置
- ✅ 与 WooCommerce 原生功能集成
- ✅ 适合快速迁移现有产品

**注意：**
- ⚠️ 显示顺序按 WC 设置的顺序
- ⚠️ 无法在 Class 页面单独调整顺序

---

### **方式 3：主题默认图片（回退方案）**

**适用场景：** 开发测试、临时占位

**工作机制：**
- 当以上两种方式都没有配置时
- 自动加载 `/assets/images/classes/{modifier}/gallery-{n}.jpg`

**文件路径示例：**
```
/assets/images/classes/tiddler/gallery-1.jpg
/assets/images/classes/tiddler/gallery-2.jpg
...
/assets/images/classes/toddler/gallery-1.jpg
...
```

---

### **优先级流程图**

```
用户访问页面
    ↓
检查 ACF gallery_images 字段
    ↓ (有图片？)
   YES → 使用 ACF 图片 ✅
    ↓ (无图片)
   NO  → 检查 WC Product Gallery
            ↓ (有图片？)
           YES → 使用 WC 图片 ✅
            ↓ (无图片)
           NO  → 使用主题默认图片 ⚠️
```

---

### **推荐配置策略**

| 场景 | 推荐方式 | 原因 |
|------|---------|------|
| 新产品正式发布 | 方式 1 (ACF) | 最灵活，SEO 最佳 |
| 快速原型/MVP | 方式 2 (WC) | 零配置，立即可用 |
| 开发环境测试 | 方式 3 (默认) | 无需上传图片 |
| 从旧站迁移 | 方式 2 (WC) | 批量导入时保留原图 |

---

### **最佳实践建议**

#### **图片规范**

| 属性 | 推荐值 |
|------|--------|
| **尺寸** | 最小 1200px 宽（支持 Retina） |
| **格式** | JPG (照片) / PNG (图标) / WebP (现代浏览器) |
| **文件大小** | 单张 ≤ 500KB (压缩后) |
| **数量** | 5-10 张（过多影响加载速度） |
| **比例** | 16:9 或 4:3 (横向) |

#### **命名规范**
```
tiddler-gym-class-action-shots-01.jpg
tiddler-gym-class-action-shots-02.jpg
...
```

#### **Alt 文本写法**
```
✅ 好: "Children enjoying Tiddler Gym obstacle course"
❌ 差: "image01.jpg" / "DSC_1234"
✅ 好: "Parent and baby during music time at TJ's Gymnastics"
```

---

## Booking 页面配置

每个课程需要一个对应的预订页面：

**自动匹配逻辑：**
1. 首先检查 ACF 字段 `booking_page_url`
2. 尝试查找 `{product-slug}-booking` 页面
3. 尝试查找 `{page-slug}-booking` 页面

**示例：**
- 产品：`tiddler-gym`
- 预订页：创建页面 slug 为 `tiddler-gym-booking`

---

## 缓存机制

系统已启用智能缓存：

| 数据类型 | 缓存时长 | 清除时机 |
|---------|---------|---------|
| 产品基本信息 | 1 小时 | 手动或更新产品时 |
| 课程时间表 | 15 分钟 | 自动或手动 |

**手动清除缓存：**
```php
// 在主题 functions.php 或调试时使用
tjs_clear_product_cache('tiddler-gym'); // 清除单个产品
tjs_clear_all_class_caches(); // 清除所有
```

**自动清除：**
- 当你在后台更新 WooCommerce 产品时，相关缓存会自动清除

---

## Modifier 样式类

系统根据产品分类自动应用 CSS modifier 类：

| 产品分类 | Modifier | Body Class |
|---------|----------|------------|
| tiddler-gym | `tiddler` | `.class-detail-page--tiddler` |
| toddler-gym | `toddler` | `.class-detail-page--toddler` |
| mini-gym | `minigym` | `.class-detail-page--minigym` |
| gymnastics | `gym` | `.class-detail-page--gym` |

**自定义新类型：**
编辑 [class-product-functions.php](inc/class-product-functions.php) 的 `tjs_get_class_modifier()` 函数：

```php
if ($cat->slug === 'your-new-category') $modifier = 'yourmodifier';
```

---

## 从旧模板迁移

如果你已有使用独立模板（template-class-toddler.php 等）的页面：

### 方法 1：切换模板（推荐）

1. 编辑现有页面
2. **Page Attributes > Template** 改为 **"Dynamic Class Detail"**
3. 确保 page slug 正确
4. 更新发布 ✅

### 方法 2：301 重定向（保持 SEO）

在 [functions.php](functions.php) 中添加：

```php
add_action('template_redirect', function() {
    if (is_page('old-toddler-gym-page')) {
        wp_redirect(home_url('/product/toddler-gym-product'), 301);
        exit;
    }
});
```

---

## 故障排除

### 问题：页面显示 "Class Not Found"

**可能原因：**
1. ❌ 页面 slug 与产品 slug 不匹配
2. ❌ 产品不存在或已删除
3. ❌ 产品不是 Variable Product 类型

**解决方案：**
1. 检查 WooCommerce 后台确认产品存在
2. 确认产品 slug 正确
3. 使用 ACF `linked_product` 字段手动关联

### 问题：图片不显示

**检查顺序：**
1. ACF `gallery_images` 字段是否配置？
2. 文件路径 `/assets/images/classes/{modifier}/gallery-{n}.jpg` 是否存在？

### 问题：样式不正确

**验证步骤：**
1. 检查 body 是否有 `class-detail-page` 类
2. 检查是否有 `class-detail-page--{modifier}` 类
3. 浏览器开发者工具查看 CSS 加载情况

---

## 性能优化建议

### 1. 启用对象缓存（生产环境推荐）
```php
// wp-config.php
define('WP_CACHE', true);
```

### 2. 使用 Redis/Memcached
安装 **Redis Object Cache** 或 **W3 Total Cache**

### 3. CDN 配置
将静态资源（图片、CSS、JS）推送到 Cloudflare/AWS CloudFront

---

## 技术架构图

```
用户访问 /product/{slug}
        ↓
WordPress Page (Dynamic Class Detail Template)
        ↓
template-class-dynamic.php
   ├─ 1. 获取 page slug
   ├─ 2. 查找匹配的 WC Product (带缓存)
   │     ├─ 直接匹配
   │     ├─ Slug 映射
   │     └─ ACF linked_product 字段
   ├─ 3. 读取 ACF 字段
   ├─ 4. 获取变体数据 (tjs_get_class_sessions)
   ├─ 5. 渲染统一 HTML
   │     ├─ Hero Section
   │     ├─ About Section
   │     ├─ Booking Table (Desktop + Mobile)
   │     ├─ Term Cards (Current + Upcoming)
   │     ├─ Terms Summary
   │     └─ Photo Gallery
   └─ 6. 输出完整页面
```

---

## 更新日志

### v2.0 (当前版本)
- ✅ 增强动态模板（Gallery + Mobile View）
- ✅ 智能缓存系统（产品 1h / 会话 15m）
- ✅ 动态 CSS modifier 检测
- ✅ 多级 Booking URL 解析
- ✅ 自动缓存清除钩子
- ✅ 完整错误处理

---

## 联系与支持

如有问题，请检查：
1. WordPress Debug Log (`/wp-content/debug.log`)
2. 浏览器控制台错误
3. WooCommerce 产品设置

**开发参考：**
- 主模板：[page-templates/template-class-dynamic.php](page-templates/template-class-dynamic.php)
- 数据函数：[inc/class-product-functions.php](inc/class-product-functions.php)
- 样式加载：[functions.php](functions.php) (tjs_scripts, tjs_body_classes)

---

**最后更新：** 2026-04-11
**版本：** 2.0.0
