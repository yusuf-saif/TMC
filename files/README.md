# TMC Documentation

This folder contains all the reference documents for the TMC MVP build.
Use these files as context in Claude Code, Cursor, or any AI coding tool.

---

## Documents

| File | Purpose | Use with |
|------|---------|---------|
| `PRD.md` | What the product does — features, modules, acceptance criteria | Stakeholder reviews, scoping decisions |
| `TRD.md` | How it's built — schema, routes, services, security, deployment | Developer reference throughout build |
| `DESIGN_SYSTEM.md` | Colour tokens, typography, CSS components, animation tokens | Every screen you build |
| `DESIGN_GUIDE.md` | Screen-by-screen design specs, brand asset usage rules | Before building any Blade/Livewire view |
| `BUILD_PHASES.md` | Phase-by-phase prompts — paste into Claude Code or Cursor | Start of each coding session |

---

## How to Use With Claude Code

### Starting a new phase

1. Open a new Claude Code session
2. Add all 5 `.md` files as context (drag into the chat or use `@` to reference)
3. Paste the **Stack Context Block** from `BUILD_PHASES.md`
4. Paste the relevant **Phase Prompt** from `BUILD_PHASES.md`
5. Let Claude Code build

### During a phase

If Claude Code loses context or starts going wrong:
1. Re-add the `.md` files as context
2. Re-paste the Stack Context Block
3. Continue from where you left off — do not start the phase over

### Design questions

When building any screen, refer to:
- `DESIGN_SYSTEM.md` for tokens and CSS
- `DESIGN_GUIDE.md` for the specific screen spec

---

## Key Rules

```
1. Complete one phase fully before starting the next
2. php artisan test must pass before moving on
3. git commit at end of every phase
4. Landing page at / must never break
5. Journal content is private — encrypted, admin blocked, no exceptions
6. All admin actions logged to audit_logs
7. All permissions enforced server-side via Laravel Policies
```

---

## Brand Assets

Located in `/public/images/` in the repo:

| File | Use |
|------|-----|
| `img1.png` | Logo mark — nav, favicon, legacy card, PWA icons |
| `img2.png` | Full logo — footer, onboarding |
| `img3.png` | Arabic calligraphy — section divider |
| `img4.png` | Botanical pattern — section background texture |

---

## Tech Stack

```
Laravel 11 · PHP 8.2+
Livewire 3 · Alpine.js · Blade
Tailwind CSS v3
MySQL 8.0+
Filament v3 (admin at /admin)
Laravel Fortify (auth)
Spatie Laravel Permission (roles)
minishlink/web-push (push notifications)
Laravel Queue + Scheduler
```
