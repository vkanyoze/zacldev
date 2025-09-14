# 🚀 Quick Start Guide - ZACL Analytics

## ⚡ 30-Second Setup

### 1. Start Server
```bash
php artisan serve
```
**URL**: `http://localhost:8000`

### 2. Login & View Analytics

#### 👤 User Analytics
- **URL**: `http://localhost:8000/dashboard`
- **Login**: `john.smith@example.com` / `password`
- **Features**: Personal spending predictions, payment forecasts, AI insights

#### 👨‍💼 Admin Analytics  
- **URL**: `http://localhost:8000/admin/dashboard`
- **Login**: Your admin credentials
- **Features**: Business intelligence, revenue forecasts, user growth predictions

### 3. Demo Data Available
- **11 Users** with realistic profiles
- **26 Cards** with international addresses
- **173 Payments** spanning 90 days
- **13 Settings** for system configuration

## 🎯 What You'll See

### User Dashboard
- 💰 **Revenue Forecast**: Next 7 days prediction
- 📊 **Payment Activity**: Expected payments & peak days
- 🤖 **AI Insights**: Personalized recommendations
- 📈 **Trend Analysis**: Growth patterns & confidence scores

### Admin Dashboard
- 🏢 **Business Metrics**: Revenue, users, payments, system load
- 📊 **Performance Analytics**: Model accuracy & data quality
- 💡 **Business Intelligence**: Strategic recommendations
- 📈 **Growth Predictions**: User acquisition & revenue forecasts

## 🔧 Troubleshooting

### Analytics Not Loading?
```bash
# Clear caches
php artisan cache:clear
php artisan view:clear

# Rebuild assets
npm run build
```

### No Data Showing?
```bash
# Check demo data
php artisan tinker
>>> App\Models\User::count()
>>> App\Models\Payment::count()
```

## 📚 Full Documentation
See `ANALYTICS_GUIDE.md` for complete instructions and technical details.

---
**🎉 Ready to explore your predictive analytics!**
