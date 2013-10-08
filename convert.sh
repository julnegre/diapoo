#/bin/bash
rep="photos/"
cd $rep

JHEAD=jhead
SED=sed
CONVERT=convert
#for f in *.jpg
#do
#        orientation=$($JHEAD -v $f | $SED -nr 's:.*Orientation = ([0-9]+).*:\1:p')
#        if [ -z $orientation ]
#        then
#                orientation=0
#        fi
#        if [ $orientation -gt 1 ]
#        then
#                echo "rotation $orientation pour $f..."
#                #mv $f $f.bak
#                #$CONVERT -auto-orient $f.bak $f
#        fi
#done
#for f in *.JPG
#do
#		$JHEAD -v $f
#        orientation=$($JHEAD -v $f | $SED -nr 's:.*Orientation = ([0-9]+).*:\1:p')
#        if [ -z $orientation ]
#        then
#                orientation=0
#        fi
#        if [ $orientation -gt 1 ]
#        then
#                echo "rotation $orientation pour $f..."
#                #mv $f $f.bak
#                #$CONVERT -auto-orient $f.bak $f
#        fi
#done


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
cd ..
chmod -Rf 777 photos
lftp ftp://login:pwd@host -e "mirror -e -R /var/www/diapo/small /www/diapo/small ; quit"
lftp ftp://login:pwd@host -e "mirror -e -R /var/www/diapo/photos /www/diapo/photos ; quit"
lftp ftp://login:pwd@host -e "mirror -e -R /var/www/diapo/too_small /www/diapo/too_small ; quit"
