#/bin/bash
rep="photos/"
cd $rep

JHEAD=jhead
SED=sed
CONVERT=convert

# rotation
jhead -autorot *jpg
jhead -autorot  *JPG

#rm small/*JPG
for fichier in `ls *JPG`
do
	if [ ! -f ../small/$fichier ]
	then
		echo "small - "$fichier
		convert $fichier -resize 25% ../small/$fichier
		
	fi
	if [ ! -f ../too_small/$fichier ]
	then
		echo "too_small - "$fichier
		convert $fichier -resize 5% ../too_small/$fichier
	fi
done

# rotation
jhead -autorot ../small/*JPG
jhead -autorot ../too_small/*JPG

#rm small/*jpg
for fichier in `ls *jpg`
do
	if [ ! -f ../small/$fichier ]
	then
		echo $fichier
		convert $fichier -resize 60% ../small/$fichier
		
	fi
	if [ ! -f ../too_small/$fichier ]
	then
		convert $fichier -resize 15% ../too_small/$fichier
	fi
done

# rotation
jhead -autorot ../small/*jpg
jhead -autorot ../too_small/*jpg

cd ..
chmod -Rf 777 photos

#lftp ftp://login:pwd@host -e "mirror -e -R /var/www/diapo/small /www/diapo/small ; quit"
#lftp ftp://login:pwd@host -e "mirror -e -R /var/www/diapo/photos /www/diapo/photos ; quit"
#lftp ftp://login:pwd@host -e "mirror -e -R /var/www/diapo/too_small /www/diapo/too_small ; quit"
