# CI/CD PIPELINE DOCUMENTATION
## JUMIANG SMART TOURISM & UMKM
**Continuous Integration & Continuous Deployment via GitHub Actions**

---

## 1. STRUKTUR PIPELINE GITHUB ACTIONS
Pipeline otomatis dieksekusi pada setiap `push` dan `pull_request` ke branch `main` dan `staging`:

```mermaid
graph LR
    PushEvent[Git Push / PR] --> LintStage[1. Code Lint & Format<br>PHP CS Fixer / Pint]
    LintStage --> StaticAnalysis[2. Static Analysis<br>PHPStan / Larastan]
    StaticAnalysis --> AutoTests[3. Automated Tests<br>PHPUnit & Feature Tests]
    AutoTests --> SecurityAudit[4. Dependency Audit<br>Composer & NPM Audit]
    SecurityAudit --> DeployStaging[5. Auto-Deploy Staging<br>SSH to Linux VPS]
```

---

## 2. ATURAN KELULUSAN PIPELINE (QUALITY GATE)
- Semua tahapan bersifat pemblokir (*blocking*). Jika satu unit test gagal, proses build digagalkan dan deploy dibatalkan secara otomatis.
