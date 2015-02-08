#!/bin/bash
#
# This script is used to split the master version of elcodi/elcodi
# into several independent repositories. It now uses git filter-branch
# to execute the split. The same result, with a little more security,
# can be achieved by using "subtree split" in git v1.8
#
echo "<div class='command-box'>"
echo "<span class='label label-default'>Build environment</span>"
echo "<span class='label label-info'>$(date)</span>"

echo "<span class='command-line'>Using token: $5</span>"
echo "<span class='command-line'>pushd /tmp</span>"
echo "<span class='command-line'>"
pushd /tmp
echo "</span>"

echo "<span class='command-line'>git clone $2 $1</span>"
echo "<span class='command-line'>"
git clone $2 $1
echo "</span>"

echo "<span class='command-line'>pushd $1</span>"
echo "<span class='command-line'>"
pushd $1
echo "</span>"
echo "</div>"


echo "<div class='command-box'>"
echo "<span class='label label-default'>Build repository</span>"
echo "<span class='label label-info'>$(date)</span>"
# Retrieve the las tag associated to HEAD, if any
TAG=$(git describe --exact-match --tags 2>/dev/null || echo "NOTAG");

# If a tag is present, we first have to REMOVE it
# and then add a tag with the same name to the HEAD
# of each splitted branch, push that branch along
# with the newly created tag to the subpackage remote
# and then repeate the process for the remaining subpackages
if [ "$TAG" == "NOTAG" ]; then
    echo "<span class='label label-warning'>No Tag</span>"
else
    echo "<span class='label label-success'>Tag [$TAG]</span>"
    echo "<span class='command-line'>git tag -d $TAG</span>"
    echo "<span class='command-line'>"
    git tag -d $TAG
    echo "</span>"
fi
echo "</div>"


# Split of all existing Bundles
for i in $(find $3 -maxdepth 0 -type d); do

    echo "<div class='command-box'>"
    echo "<span class='label label-default'>Split [$i]</span>"
    echo "<span class='label label-info'>$(date)</span>"
    TMP_REMOTE=$4
    REMOTE=${TMP_REMOTE/\$i/$i}

    # Split the main repo according to the subpackage and
    # put the resulting commits in separate branch
    echo "<span class='command-line'>git subtree split -q --prefix=$i --branch=branch-$i</span>"
    echo "<span class='command-line'>"
    git subtree split -q --prefix=$i --branch=branch-$i
    echo "</span>"

    # Remove current remote
    echo "<span class='command-line'>git remote rm origin</span>"
    echo "<span class='command-line'>"
    git remote rm origin
    echo "</span>"

    # Add a remote named after current subpackage
    echo "<span class='command-line'>git remote add origin $REMOTE</span>"
    echo "<span class='command-line'>"
    git remote add origin $REMOTE
    echo "</span>"

    # Checkout the recently filtered branch (may be optional)
    echo "<span class='command-line'>git checkout branch-$i</span>"
    echo "<span class='command-line'>"
    git checkout branch-$i
    echo "</span>"

    # Push the filtered commits to remote master
    echo "<span class='command-line'>git push origin branch-$i:master</span>"
    echo "<span class='command-line'>"
    git push origin branch-$i:master
    echo "</span>"

    # If a tag exists, we need to create a new one named $TAG
    # pointing to the HEAD of the newly splitted commits and
    # then push it to the subpackage remote
    if [ "$TAG" != "NOTAG" ]
    then

        echo "<span class='command-line'>git tag -a $TAG -m 'Created tag $TAG'</span>"
        echo "<span class='command-line'>"
        git tag -a $TAG -m "Created tag $TAG"
        echo "</span>"

        echo "<span class='command-line'>COMMIT=$(git rev-list HEAD -1)</span>"
        echo "<span class='command-line'>"
        COMMIT=$(git rev-list HEAD -1)
        echo "</span>"

        echo "<span class='command-line'>git push origin $TAG</span>"
        echo "<span class='command-line'>"
        git push origin $TAG
        echo "</span>"

        # Tag must be deleted every time since we do not
        # want to push commits belonging to other subpackages
        # to current subpackage remote
        echo "<span class='command-line'>git tag -d $TAG</span>"
        echo "<span class='command-line'>"
        git tag -d $TAG
        echo "</span>"
    fi

    # Go back to HEAD in master of the main repo
    echo "<span class='command-line'>git checkout master</span>"
    echo "<span class='command-line'>"
    git checkout master
    echo "</span>"
    echo "</div>"
done

echo "<div class='command-box'>"
echo "<span class='label label-default'>Remove environment</span>"
echo "<span class='label label-info'>$(date)</span>"
echo "<span class='command-line'>rm -rf $1</span>"
echo "<span class='command-line'>"
rm -rf $1
echo "</span>"
echo "</div>"