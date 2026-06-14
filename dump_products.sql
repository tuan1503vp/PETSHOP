-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: petshop_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` enum('product','service') NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Thức ăn cho Chó','product','Các loại hạt, pate, thịt hộp cho chó'),(2,'Thức ăn cho Mèo','product','Hạt, súp thưởng, pate cho mèo'),(3,'Phụ kiện','product','Vòng cổ, dây dắt, balo, khay vệ sinh'),(5,'Khám, chữa bệnh','service',''),(6,'Chăm sóc','service',''),(7,'Huấn luyện','service',''),(8,'Chụp ảnh','service',''),(9,'Trông giữ','service',''),(10,'Chó','product',''),(11,'Mèo','product',''),(12,'Thuốc','product',''),(13,'Vắc - Xin','product','');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expiry_date` date DEFAULT NULL,
  `batch_number` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (6,1,'Royal Canin Medium Adult','Mô tả: Thức ăn khô dành cho chó trưởng thành giống vừa (11–25kg), hỗ trợ tiêu hóa khỏe mạnh, tăng cường miễn dịch.\r\n\r\nĐặc điểm nổi bật: hạt nhỏ dễ nhai, giàu protein chất lượng cao, bổ sung omega-3 cho da và lông bóng mượt.',630000.00,196,'1778744184_thucanpet1.png','2026-05-14 07:36:24',NULL,NULL),(7,1,'Royal Canin Maxi Puppy 15kg','Dành cho chó con từ 2–15 tháng tuổi, giàu DHA và canxi.',1750000.00,200,'1778744336_thucanpet2.png','2026-05-14 07:38:56',NULL,NULL),(8,1,'SmartHeart Adult Beef Flavor 20kg','Thức ăn khô vị bò cho chó trưởng thành, giàu omega-3 và vitamin E.',595000.00,199,'1778744375_thucanpet3.png','2026-05-14 07:39:35',NULL,NULL),(9,1,'Ganador Premium Adult Lamb & Rice 20kg','Công thức thịt cừu và gạo, phù hợp cho chó nhạy cảm với protein gà.',1090000.00,200,'1778744421_thucanpet4.png','2026-05-14 07:40:21',NULL,NULL),(10,1,'Reflex Plus Puppy Chicken 3kg','Thức ăn khô cao cấp cho chó con, vị gà, bổ sung prebiotic và DHA.',350000.00,194,'1778744454_thucanpet5.png','2026-05-14 07:40:54',NULL,NULL),(11,2,'Royal Canin Kitten','Thức ăn khô cho mèo con từ 2–12 tháng tuổi, giàu DHA và canxi, hỗ trợ phát triển xương và trí não.',130000.00,194,'1778745042_thucanmeo1.png','2026-05-14 07:50:42',NULL,NULL),(12,2,'Royal Canin Indoor 27','Dành cho mèo trưởng thành nuôi trong nhà, công thức giảm mùi phân và hỗ trợ tiêu hóa.',125000.00,199,'1778745086_thucanmeo2.png','2026-05-14 07:51:26',NULL,NULL),(13,2,'Royal Canin Mother & Babycat','Thức ăn cho mèo mẹ và mèo con mới cai sữa, hạt nhỏ dễ ăn, hỗ trợ miễn dịch.',130000.00,200,'1778745150_thucanmeo3.png','2026-05-14 07:52:30',NULL,NULL),(14,2,'Whiskas Pate Cho Mèo Con','Pate cho mèo con từ 2–12 tháng tuổi, giàu DHA và taurine, giúp mắt sáng khỏe.',110000.00,190,'1778745233_thucanmeo4.png','2026-05-14 07:53:53',NULL,NULL),(15,10,'Akita Inu (犬) – chó vùng Akita, Nhật Bản.','Ngoại hình: Cao 60–75 cm, nặng 35–60 kg, lông kép dày, đuôi cuộn tròn trên lưng.\r\nTính cách: Trung thành, thông minh, độc lập, gắn bó sâu sắc với chủ.\r\nSức khỏe: Khỏe mạnh, chịu lạnh tốt, cần không gian rộng để vận động.\r\nBiểu tượng văn hóa: Gắn liền với câu chuyện Hachiko – chú chó trung thành chờ chủ suốt 9 năm.',29997000.00,200,'1778749361_Akita.jpg','2026-05-14 09:02:41',NULL,NULL),(16,10,'Central Asian Shepherd Dog (Alabai).','Ngoại hình: Thân hình to lớn, cơ bắp săn chắc, đầu to, mõm rộng, mắt sâu.\r\nTính cách:\r\n + Trung thành tuyệt đối với chủ.\r\n + Thông minh, nhanh nhẹn, dễ huấn luyện.\r\n + Bản năng bảo vệ mạnh mẽ, đôi khi hơi hung hãn với người lạ.\r\nKhả năng thích nghi: Chịu lạnh rất tốt, chịu nóng trung bình.',14998000.00,200,'1778749579_Alabai.jpg','2026-05-14 09:06:19',NULL,NULL),(17,10,'American Bully','Ngoại hình: Thân hình cơ bắp, ngực rộng, đầu to vuông vức, lông ngắn mượt.\r\nTính cách: Thân thiện, trung thành, hiền hòa hơn Pit Bull, nhưng cần huấn luyện và xã hội hóa từ nhỏ.\r\nTuổi thọ: 10–14 năm.',9000000.00,200,'1778749768_American-Bully.jpg','2026-05-14 09:09:28',NULL,NULL),(18,10,'Chó Bichon','Ngoại hình: Bộ lông trắng muốt, xoăn tít như bông gòn; mắt đen tròn; dáng nhỏ gọn (3–5 kg).\r\nTính cách: Thân thiện, hoạt bát, thông minh, rất gắn bó với chủ.\r\nTuổi thọ: 12–15 năm.\r\nChăm sóc đặc biệt: Lông cần được chải và grooming thường xuyên (2–4 tuần/lần).',10000000.00,199,'1778749888_Bichon.jpg','2026-05-14 09:11:28',NULL,NULL),(19,10,'Border Collie','Thông minh & nhanh nhẹn: Rất giỏi trong các môn thể thao chó (agility, obedience).\r\nNăng lượng cao: Cần nhiều vận động, không phù hợp với người ít thời gian.\r\nTrung thành & tình cảm: Gắn bó chặt chẽ với chủ, thích làm việc và học hỏi.\r\nHuấn luyện: Dễ huấn luyện nhưng cần kiên nhẫn và nhất quán.',17000000.00,200,'1778776909_Border-Collie.jpg','2026-05-14 09:12:31',NULL,NULL),(20,11,'Khao-Manee','Thân thiện, tình cảm: Rất gắn bó với chủ, thích sự chú ý.\r\nThông minh, hiếu kỳ: Dễ huấn luyện, thích khám phá.\r\nNăng động: Cần không gian chơi đùa, phù hợp với gia đình yêu thích vận động.',80000000.00,200,'1778750033_Khao-Manee.jpg','2026-05-14 09:13:53',NULL,NULL),(21,11,'Mèo Thần Miến Điện (Birman)','Thân thiện, hiền hòa: Rất gắn bó với chủ, thích được vuốt ve.\r\nThông minh, dễ huấn luyện: Có thể học các trò đơn giản.\r\nNăng động vừa phải: Không quá hiếu động như mèo Xiêm, nhưng vẫn thích chơi đùa.\r\nPhù hợp: Với gia đình có trẻ nhỏ hoặc người lớn tuổi vì tính cách dịu dàng.',15000000.00,199,'1778750102_meo-than-mien-dien-Birman.jpg','2026-05-14 09:15:02',NULL,NULL),(22,11,'Mèo Ba Tư (Persian Cat)','Hiền lành, tình cảm: Rất gắn bó với chủ, thích được vuốt ve.\r\nÍt vận động: Thích nằm nghỉ ngơi, phù hợp với môi trường sống trong nhà.\r\nDễ nuôi: Không quá hiếu động, thích hợp với người bận rộn.\r\nCần chăm sóc lông thường xuyên: Chải lông mỗi ngày để tránh rối và rụng.',4998000.00,199,'1778750154_Meo-ba-tu.jpg','2026-05-14 09:15:54',NULL,NULL),(23,11,'Mèo Tuxedo','Thông minh, nhanh nhẹn: Nhiều nghiên cứu cho thấy mèo tuxedo thường lanh lợi hơn.\r\nThân thiện, tình cảm: Gắn bó với chủ, thích chơi đùa.\r\nNăng động: Thích khám phá, leo trèo.\r\nĐặc biệt: Có nhiều câu chuyện dân gian cho rằng mèo tuxedo mang lại may mắn.',596000.00,187,'1778750207_meo-tuxedo.jpg','2026-05-14 09:16:47',NULL,NULL),(24,11,'Mèo Anh lông ngắn (British Shorthair)','Hiền lành, điềm tĩnh: Rất gắn bó với chủ, ít quậy phá.\r\nThân thiện: Hòa đồng với trẻ em và các vật nuôi khác.\r\nÍt vận động: Thích nằm nghỉ ngơi, phù hợp nuôi trong nhà.\r\nThông minh: Dễ huấn luyện, nhưng đôi khi hơi “lười biếng”.',2997000.00,83,'1778750275_Meo-Anh-Long-Ngan.jpg','2026-05-14 09:17:55',NULL,NULL),(25,12,'Frontline Plus (Merial)','Nhỏ gáy diệt bọ chét, ve; hiệu lực 1 tháng',150000.00,1000,'1781258326_FrontlinePlus.jpg','2026-06-12 09:58:46',NULL,NULL),(26,12,'Advocate (Bayer)','Phòng giun sán, bọ chét, ve, bệnh tim; dạng spot-on',200000.00,996,'1781258458_thuuoc2.jpg','2026-06-12 10:00:58',NULL,NULL),(27,13,'Nobivac DHPPi','Vắc xin 4 bệnh cốt lõi cho chó, được sử dụng rất phổ biến trong các phòng khám thú y.\r\nPhòng bệnh:\r\nCare (Distemper)\r\nViêm gan truyền nhiễm\r\nParvovirus\r\nParainfluenza',250000.00,1000,'1781367492_xmbJ5HIKYc3BGlAd6o7Mo2FjP6Y16TcuFVuuVqJouTTefs9-1KDlkIgPZ_v_QHyW-g6DzYcZp3vYzYzcoCb6NbD0QIG5VwJnf2TDygKqGPNgYHtSuV2gF6c_4uz4FrvJzlDl9dH6ZsnRX-7RShu8witAq7dDMk2gEkMQUi5uhrQ.jpg','2026-06-13 16:09:03','2027-06-13','VNG-2025A'),(28,13,'Nobivac Rabies','Vắc xin phòng dại bắt buộc cho chó và được khuyến nghị cho mèo.',120000.00,200,'1781367769_VviVIISiiSjtJ0Xj6Qrg_GfHDsZBB3knVUbNiSAxN4VmqAwhy72gdzDWVHN5Ww7k74H8tpQimp1kHd9WMiLAJ08wBtJs4Uy1qd_scKtKZ1FQBr1IvGkB0QKmrJD4DVabJ1IJeOI_LlcOb25PpTh4iPc_UL3GaXKpzPTd9czFxFc.jpg','2026-06-13 16:09:03','2027-06-13','FEL-2026B'),(29,13,'Vanguard Plus','Dòng vắc xin nổi tiếng của Zoetis, thường dùng cho chó con.\r\nPhòng bệnh:\r\nCare\r\nViêm gan\r\nParvovirus\r\nParainfluenza',280000.00,100,'1781367873_oszpPgh0uFb3VpS5vDVJ5mBCmZnuZedjxOnuGGRWoxueaHPE55zuet8Hsu7zm4vJlSjZ9sKo9neu67ykBEB7UUyYgEIT9AUFTje_k-vvbKdEKSyrp3VMOF8xeewQfYhwUY4kwPyx2mqQH4ArFjrSD7A1eFIbUhGSffnKqFui1GE.jpg','2026-06-13 16:09:03','2027-06-13','RAB-2026C'),(30,13,'Vanguard HTLP 5/CV-L (V8)','Loại vắc xin đa giá bảo vệ chó trước nhiều bệnh truyền nhiễm nguy hiểm.\r\nPhòng bệnh:\r\nCare\r\nViêm gan\r\nParvovirus\r\nParainfluenza\r\nCoronavirus\r\nLeptospira',300000.00,300,'1781368045_7j_7LAKDJORQUdMQwb0szYuNmq5mh8ME8cJwUo7qa5CMaoHEorP72BsGh4HySp4F3SA09LRxlxf8EBWEu3IMLKwwI3VU8P7v077_olcUs-KBg5bn6kLpURXMhgj-skaktoguhBz3yy9pelEHpwegisa3-VLCPygDe1TUchmSoIk.jpg','2026-06-13 16:27:25','2027-07-20',NULL),(31,13,'Vanguard Plus 5 L4 CV (V10)','Một trong những loại vắc xin phổ biến nhất cho chó nuôi gia đình.\r\nPhòng 10 bệnh truyền nhiễm phổ biến ở chó',350000.00,1000,'1781368154_vo93U5E45RIh41p-lNdJq2OFgxIs4C976Mns_crYzrS_wY-unkSu1u2w_TY4ITnEfGLvg5qKBNc16BggD36B5nDfA7KMj5IhVUMzBnLhlh6aTn7qUlAQDq_-WqG1Q1_AApNqhUDRnVJpnP8Ml_o6ObUzILjnk1pN-Uu7bUf6g9k.jpg','2026-06-13 16:29:14','2028-07-13',NULL),(32,13,'Nobivac KC','Khuyến nghị cho chó thường xuyên tiếp xúc với nhiều chó khác hoặc gửi khách sạn thú cưng.\r\nPhòng bệnh:\r\nHo cũi chó (Kennel Cough)',250000.00,500,'1781368252_HTm5Ors6BZgk75h0U1uPRmx1SRqLOLGxXpLzn0oianrLaRsKloygd7-XvEGqDNg-VUadSVa5H0avTKFUuW_iUq8cVSx8jlHb2nwgjLxX5fFi0_Wya57nbJqWTTfI2Bz3nHPuTHG587sukC75GquHLttASor2TrT7nkNo-mFjZbA.jpg','2026-06-13 16:30:52','2027-06-17',NULL),(33,13,'Purevax RCP','Vắc xin 3 bệnh cốt lõi cho mèo, được sử dụng rộng rãi tại các phòng khám thú y.\r\nPhòng bệnh:\r\nRhinotracheitis\r\nCalicivirus\r\nPanleukopenia',300000.00,1000,'1781368330_RHpH1AO_8U3Rua6KZXPI5BYRpL-AIqqMEi3-lZdZ-zATdAC5b2FXInEXLddciAt1HGt6cS2j-RTcExOWVyG7HLizhK-dfmXyo8qFC39gS2gFKJFv0FsoOYvyz04zSCpwBF6pZSAZ18RwkQ-58IyeSqBiyZQMaLcXpvAbH5h3NhQ.jpg','2026-06-13 16:32:10','2027-07-22',NULL),(34,13,'Purevax RCP-FeLV','Dành cho mèo có nguy cơ tiếp xúc với mèo khác hoặc mèo nuôi thả.\r\nPhòng bệnh:\r\nRhinotracheitis\r\nCalicivirus\r\nPanleukopenia\r\nBạch cầu mèo (FeLV)',450000.00,600,'1781368410_z0rwgQy0U2O8JkC-mnCM80aMd3n4-yr-7pYmAAaKanwyrrWnKdOKFkQBPpqWaBfquK3A9iURY5PvIze1s9EkdN1-4VoGl01-e2tQw5cKVix97tR567DcwYLdlHcJQ9rWrOu3qiDAy8YCKlgRtHufI3kUojCHlrZNMXHrHl4aqsE.jpg','2026-06-13 16:33:30','2027-07-13',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-14 13:11:06
