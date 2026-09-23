1. Ambil project dari GitHub
Clone and open the existing GitHub repository in this workspace. Inspect the project structure, dependencies, environment configuration, and existing documentation first. Do not modify any code yet. Explain briefly how the project is currently structured and how to run it locally.

2. Setelah clone, jalankan project
Set up this project locally and run it. Install the required dependencies if needed. Check for configuration or environment issues. Do not change the architecture or existing code unless necessary to make the project run.

3. Sebelum mengerjakan task
Before making changes, inspect the existing codebase and identify the files and components related to this task. Follow the existing architecture, naming conventions, and coding style. Do not rewrite unrelated parts of the project.

4. Mengerjakan fitur
Implement this feature: [DESCRIBE TASK].
First inspect the existing implementation and then make the smallest necessary changes. Keep the current architecture and avoid modifying unrelated functionality. After implementation, run the appropriate checks/build to verify it works.

5. Setelah selesai edit — cek perubahan
Review all changes you made for this task. Check for bugs, broken functionality, inconsistent code, unused imports, and potential side effects. Run the appropriate build, lint, or tests. Fix any issues you find.

6. Update project dari GitHub sebelum mulai kerja

Ini penting kalau beberapa orang kerja bersamaan:

Update this local repository with the latest changes from GitHub. Check the current branch and local changes first. Do not overwrite or discard my uncommitted changes. Resolve conflicts carefully if necessary.
7. Setelah selesai kerja — lihat perubahan
Review the Git diff and summarize exactly what was changed in this task. Make sure there are no unrelated changes. Do not commit or push anything yet.
8. Commit perubahan
Prepare a clean Git commit for the changes from this task. Use a concise conventional commit message describing what was actually changed. Do not include unrelated files or changes.

Contoh hasil commit:
feat: add incoming letter management
fix: fix document upload validation
feat: add teacher role permissions
refactor: improve letter service

9. Push ke GitHub
Push the completed changes to the current GitHub branch. Before pushing, verify the working tree, branch, and latest remote changes. Do not force push.

alur : 

git pull
   ↓
buat branch sendiri
   ↓
kerjakan task
   ↓
test
   ↓
git diff
   ↓
commit
   ↓
push branch
   ↓
Pull Request
   ↓
review
   ↓
merge ke main