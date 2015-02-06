#!/bin/bash
#
# This script is used to split the master version of elcodi/elcodi
# into several independent repositories. It now uses git filter-branch
# to execute the split. The same result, with a little more security,
# can be achieved by using "subtree split" in git v1.8
#
echo "> pushd /tmp"
pushd /tmp

echo "> git clone $2 $1"
git clone $2 $1

echo "pushd $1"
pushd $1

# Retrieve the las tag associated to HEAD, if any
TAG=$(git describe --exact-match --tags 2>/dev/null || echo "NOTAG");

# If a tag is present, we first have to REMOVE it
# and then add a tag with the same name to the HEAD
# of each splitted branch, push that branch along
# with the newly created tag to the subpackage remote
# and then repeate the process for the remaining subpackages
if [ "$TAG" == "NOTAG" ]; then
    echo "<span class='label label-default'>Tag [$TAG]</span>"
else
    git tag -d $TAG
    echo "> tag -d $TAG"
    echo "<span class='label label-default'>No tag</span>"
fi

# Split of all existing Bundles
echo "> find $3 -maxdepth 0 -type d"
find $3 -maxdepth 0 -type d
for i in $(find $3 -maxdepth 0 -type d); do

    echo "<span class='label label-default'>Split [$3]</span>"
    REMOTE=$4
    REMOTE=${REMOTE/\$i/$i}

    # Split the main repo according to the subpackage and
    # put the resulting commits in separate branch
    echo "> git subtree split -q --prefix=$3/$i --branch=branch-$i"
    git subtree split -q --prefix=$i --branch=branch-$i

    # Remove current remote
    echo "> git remote rm origin"
    git remote rm origin

    # Add a remote named after current subpackage
    echo "> git remote add origin $REMOTE"
    git remote add origin $REMOTE

    # Checkout the recently filtered branch (may be optional)
    echo "> git checkout branch-$i"
    git checkout branch-$i

    # Push the filtered commits to remote master
    echo "> git push origin branch-$i:master"
    git push origin branch-$i:master

    # If a tag exists, we need to create a new one named $TAG
    # pointing to the HEAD of the newly splitted commits and
    # then push it to the subpackage remote
    if [ "$TAG" != "NOTAG" ]
    then

        echo "> git tag -a $TAG -m 'Created tag $TAG'"
        git tag -a $TAG -m "Created tag $TAG"

        echo "> COMMIT=$(git rev-list HEAD -1)"
        COMMIT=$(git rev-list HEAD -1)

        echo "> git push origin $TAG"
        git push origin $TAG

        # Tag must be deleted every time since we do not
        # want to push commits belonging to other subpackages
        # to current subpackage remote
        echo "> git tag -d $TAG"
        git tag -d $TAG
    fi

    # Go back to HEAD in master of the main repo
    echo "> git checkout master"
    git checkout master
done

echo "> rm -rf $1"
rm -rf $1