# This is how to sync with git. Make sure you are in the correct directory.

git add .
git commit -m "Addition"
git push origin main

#Rolling back to a previous version

# ==========================================
###### Method 1: Using git checkout #####
# 1. Find Commit ID: Get the commit ID of the version you want to roll back to by using:
git log

# 2. Checkout Commit: Switch to that commit:
git checkout commit_id
# =========================================


# ==========================================
###### Method 2: Using git revert #####
# If you want to create a new commit that undoes the changes of a previous commit:

# 1. Find Commit ID: Get the commit ID to revert by using:
git log

# 2. Revert Commit: Create a new commit that reverts the changes:
git revert commit_id
# ==========================================


# ==========================================
##### Method 3: Using git reset #####
# If you want to remove all commits after a certain point and reset your branch to a previous state:

# 1. Find Commit ID: Get the commit ID to reset to by using:
git log

# 2. Reset Branch: Reset your branch to the specified commit:
git reset --hard commit_id
# ==========================================


# ==========================================
###### Method 4: Using Visual Studio Code
1. View Commit History: In Visual Studio Code, open the Source Control view.
2. Open Timeline: Right-click on the file or project and select Open Timeline.
3. Select Version: Browse through the timeline to find the commit you want to revert to.
4. Checkout or Revert: You can either checkout the commit or revert changes from the timeline view.
# ==========================================